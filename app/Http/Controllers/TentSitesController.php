<?php

namespace App\Http\Controllers;

use App\Models\TentSites;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Validator;
use App\Notifications\NewTentSite;

class TentSitesController extends Controller
{
    use RestControllerTrait;


    /* @var TentSites MODEL */
    const MODEL = 'App\Models\TentSites';

    protected $validationRules = ['photo' => 'required', 'title' => 'required'];


    public function store(Request $request)
    {
        /**
         * @var TentSites $m
         */
        $m = self::MODEL;
        try {
            // Receive file
            /**
             * @var Validator $v
             */
            $v = \Validator::make($request->all(), $this->validationRules);
            if ($v->fails()) {
                throw new \Exception("ValidationException");
            }

            // Save data and use id to store image
            $post = $request->all();

            if (isset($post['photo'])) {
                unset($post['photo']);
            }
            $data = $m::create($post);

            if ($request->hasFile('photo')) {
                $imageName = $data->getAttribute('id') . '.' .
                    $request->file('photo')->getClientOriginalExtension();
                $request->file('photo')->storePubliclyAs(env('TENT_SITE_PHOTO_DIR'), $imageName);
                $data->setAttribute('img_location', $imageName);
                $data->save();
            }

            // Notify slack channel
            Notification::send(TentSites::find($data->getAttribute('id')), new NewTentSite($data));

            return $this->createdResponse($data);
        } catch (\Exception $ex) {
            $data = ['form_validations' => $v->errors(), 'exception' => $ex->getMessage()];
            return $this->clientErrorResponse($data);
        }

    }

    /**
     * @param null $lat Latitude
     * @param null $lng Longitude
     * @param null $rad Radius in kilometers
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($lat = null, $lng = null, $rad = null)
    {

        if(!is_null($lat) && !is_null($lng) && !is_null($rad)) {
            $tentSites = $this->getWithinArea($lat, $lng, $rad);
        } else {
            $m = self::MODEL;
            $tentSites= $m::all();
        }
        return $this->listResponse($tentSites);
    }


    /**
     * @param $lat
     * @param $lng
     * @param $rad
     * @return mixed
     */
    private function getWithinArea($lat, $lng, $rad) {

        $earthRadius = 6371;  // earth's mean radius, km

        // first-cut bounding box (in degrees)
        $maxLat = $lat + rad2deg($rad/$earthRadius);
        $minLat = $lat - rad2deg($rad/$earthRadius);
        $maxLng = $lng + rad2deg(asin($rad/$earthRadius) / cos(deg2rad($lat)));
        $minLng = $lng - rad2deg(asin($rad/$earthRadius) / cos(deg2rad($lat)));
        $m = self::MODEL;

        return DB::table($m::DB)
            ->whereBetween('latitude', [$minLat, $maxLat])
            ->whereBetween('longitude', [$minLng, $maxLng])
            ->get();
    }

}