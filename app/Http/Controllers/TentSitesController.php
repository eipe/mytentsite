<?php

namespace App\Http\Controllers;

use App\Events\NewTentSiteRegistered;
use App\Models\Tag;
use App\Models\TentSites;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Validator;


class TentSitesController extends Controller
{
    use RestControllerTrait;


    /* @var TentSites MODEL */
    const MODEL = 'App\Models\TentSites';
    protected $validationRules = ['photo' => 'required', 'caption' => 'required'];


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

            // Decode photo dataURL
            $photo = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $post['photo']));

            // Unset photo from post as location will be added later on
            unset($post['photo']);

            // We don't want to store the api token
            unset($post['api_token']);

            // Set approved to false as default - could this be defined in table?
            $post['approved'] = false;

            $data = $m::create($post);

            // Force all photos to be .jpg formatted
            $imageName = $data->getAttribute('id') . '.jpg';

            // Because we don't use storage to store photo we need to make sure the directory exists
            Storage::disk('public')->makeDirectory(env('TENT_SITE_PHOTO_DIR'));
            imagejpeg(
                imagecreatefromstring($photo),
                storage_path('app/public') . env('TENT_SITE_PHOTO_DIR') . $imageName, 70);
            $this->makeThumbnail($imageName);

            $data->setAttribute('img_location', $imageName);
            $data->setAttribute('thumbnail_location', $imageName);
            $data->setAttribute('reported_by', Auth::user()->getAttribute('id'));
            $data->save();

            if(isset($post['tags'])) {
                Tag::saveTentSiteTags($data, $post['tags']);
            }

            // Fire event
            event(new NewTentSiteRegistered($data));

            return $this->createdResponse($data);
        } catch (\Exception $ex) {
            $data = ['form_validations' => $v->errors(), 'exception' => $ex->getMessage()];
            return $this->clientErrorResponse($data);
        }

    }

    private function makeThumbnail($originalPhotoName) {
        // Fetch the original photo
        $originalPhoto = imagecreatefromjpeg(
            storage_path('app/public') . env('TENT_SITE_PHOTO_DIR') . $originalPhotoName
        );

        $originalWidth = imagesx($originalPhoto);
        $originalHeight = imagesy($originalPhoto);

        // 4:3 ratio
        // 800px width secures that thumbnail is larger than the smallest viewport
        $thumbnailWidth = 800;
        $thumbnailHeight = floor($originalHeight * ($thumbnailWidth / $originalWidth));

        $virtualPhoto = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);
        imagecopyresampled(
            $virtualPhoto, $originalPhoto, 0, 0, 0, 0,
            $thumbnailWidth, $thumbnailHeight, $originalWidth, $originalHeight
        );

        // Because we don't use storage to store photo we need to make sure the directory exists
        Storage::disk('public')->makeDirectory(env('TENT_SITE_THUMBNAIL_DIR'));
        imagejpeg($virtualPhoto, storage_path('app/public') . env('TENT_SITE_THUMBNAIL_DIR') . $originalPhotoName, 70);
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
            $tentSites= $m::query()->where('approved', 1)->latest()->paginate(9);
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
            ->where('approved', 1)
            ->latest()
            ->get();
    }

    public function getUserTentSites() {
        $m = self::MODEL;
        return $this->listResponse($m::withTrashed()->where('reported_by', Auth::id())->latest()->get());
    }

    public function getBookmarkedTentSites() {
        $m = self::MODEL;
        return $this->listResponse(
            $m::query()
                ->where('approved', 1)
                ->join('likes', function($join) {
                    /* @var JoinClause $join */
                    $join->on('tent_sites.id', '=', 'likes.tent_sites_id')
                        ->where('likes.user_id', '=', Auth::id())
                        ->whereNull('likes.deleted_at');
                })
                ->select('tent_sites.*')
                ->get()
        );
    }

    public function getUnapproved() {
        $m = self::MODEL;
        return $this->listResponse($m::query()->where('approved', 0)->oldest()->get());
    }

    public function approve($id) {
        try {
            $m = self::MODEL;
            /* @var TentSites $tentSite */
            $tentSite = $m::get()->where('id', $id)->first();
            $tentSite->setAttribute('approved', true);
            $tentSite->save();
            \Log::info('Tent site #'. $id .' approved.' );
            return $this->showResponse($tentSite);
        } catch(\Exception $exception) {
            return $this->clientErrorResponse(['exception' => $exception->getMessage()]);
        }
    }

    public function deny($id) {
        try {
            $m = self::MODEL;
            /* @var TentSites $tentSite */
            $tentSite = $m::get()->where('id', $id)->first();
            $tentSite->setAttribute('approved', -1);
            $tentSite->save();
            \Log::info('Tent site #'. $id .' denied.' );
            return $this->deletedResponse();
        } catch(\Exception $exception) {
            return $this->clientErrorResponse(['exception' => $exception->getMessage()]);
        }
    }

    public function delete($id) {
        try {
            $m = self::MODEL;
            /* @var TentSites $tentSite */
            $tentSite = $m::query()->where('id', $id)->first();
            $tentSite->delete();
            \Log::info('Tent site #'. $id .' deleted' );
            return $this->showResponse($tentSite);
        } catch(\Exception $exception) {
            return $this->clientErrorResponse(['exception' => $exception->getMessage()]);
        }
    }

    public function restore($id) {
        try {
            $m = self::MODEL;
            /* @var TentSites $tentSite */
            $tentSite = $m::withTrashed()->where('id', $id)->first();
            $tentSite->restore();
            \Log::info('Tent site #'. $id .' deleted' );
            return $this->showResponse($tentSite);
        } catch(\Exception $exception) {
            return $this->clientErrorResponse(['exception' => $exception->getMessage()]);
        }
    }

}