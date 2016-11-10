<?php

namespace App\Http\Controllers;

use App\Models\TentSites;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class TentSitesController extends Controller
{
    use RestControllerTrait;
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
                $imageName = env('TENT_SITE_PHOTO_DIR') . $data->getAttribute('id') . '.' .
                    $request->file('photo')->getClientOriginalExtension();
                \Storage::put(env('TENT_SITE_PHOTO_DIR') . $imageName, $request->file('photo'));
                $data->setAttribute('img_location', $imageName);
                $data->save();
            }

            return $this->createdResponse($data);
        } catch (\Exception $ex) {
            $data = ['form_validations' => $v->errors(), 'exception' => $ex->getMessage()];
            return $this->clientErrorResponse($data);
        }

    }

/*
    public function index($lat, $lng, $rad)
    {

        $this->getWithinArea($lat, $lng, $rad);
        echo $lat;
        echo $lng;
        echo $rad;
        exit;
        dd($post);

    }
*/
}
/*

    private function getWithinArea($lat, $lng, $rad) {

        $earthRadius = 6371;  // earth's mean radius, km

        // first-cut bounding box (in degrees)
        $maxLat = $lat + rad2deg($rad/$earthRadius);
        $minLat = $lat - rad2deg($rad/$earthRadius);
        $maxLon = $lng + rad2deg(asin($rad/$earthRadius) / cos(deg2rad($lat)));
        $minLon = $lng - rad2deg(asin($rad/$earthRadius) / cos(deg2rad($lat)));

        $sql = "Select Id, Postcode, Lat, Lon,
                   acos(sin(:lat)*sin(radians(Lat)) + cos(:lat)*cos(radians(Lat))*cos(radians(Lon)-:lon)) * :R As D
            From (
                Select Id, Postcode, Lat, Lon
                From MyTable
                Where Lat Between :minLat And :maxLat
                  And Lon Between :minLon And :maxLon
            ) As FirstCut
            Where acos(sin(:lat)*sin(radians(Lat)) + cos(:lat)*cos(radians(Lat))*cos(radians(Lon)-:lon)) * :R < :rad
            Order by D";
        $params = [
            'lat'    => deg2rad($lat),
            'lon'    => deg2rad($lon),
            'minLat' => $minLat,
            'minLon' => $minLon,
            'maxLat' => $maxLat,
            'maxLon' => $maxLon,
            'rad'    => $rad,
            'R'      => $R,
        ];
        $points = $db->prepare($sql);
        $points->execute($params);
    }
}
/*
 *








?>

<html>
<table>
    <? foreach ($points as $point): ?>
    <tr>
        <td><?= $point->Postcode ?></td>
        <td><?= number_format($point->Lat,4) ?></td>
        <td><?= number_format($point->Lon,4) ?></td>
        <td><?= number_format($point
 */
