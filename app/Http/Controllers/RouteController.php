<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Route;
use App\RoutePoint;

class RouteController extends Controller
{
    function show($id){

      $maxLng = -180;
      $minLng = 180;
      $maxLat = -90;
      $minLat = 90;

      \DB::enableQueryLog();

      $route = Route::find($id);
      $points = RoutePoint::where('route_id', '=', $id)->get(['lng','lat']);

      foreach ($points as $point) {
        $maxLng = $point['lng'] > $maxLng? $point['lng']: $maxLng;
        $maxLat = $point['lat'] > $maxLat? $point['lat']: $maxLat;
        $minLng = $point['lng'] < $minLng? $point['lng']: $minLng;
        $minLat = $point['lat'] < $minLat? $point['lat']: $minLat;
      }

      return view('index', compact('route', 'points', 'maxLng', 'maxLat', 'minLng', 'minLat'));
    }

    function index(){
      return view('index');
    }

    function gpsFromPostcode($postcode){
      $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".
        urlencode($postcode)."&key=" . env('GEOCODE_API');

      $contextOptions = array(
        "ssl" => array(
        "verify_peer"      => false,
        "verify_peer_name" => false,
         ),
      );

      $postcodeJson = json_decode(file_get_contents($url, false,
        stream_context_create($contextOptions)));

        $location = !empty($postcodeJson->results)?
          $postcodeJson->results[0]->geometry->location:null;

      return $location;
    }

    function submit(Request $request){

      if ($request->has('postcode')){
        //A postcode has been input
        $distance = $request->input('distance');
        $postcode = $request->input('postcode');

        $location = $this->gpsFromPostcode($postcode);

        if ($location == null){
          return redirect('/')->with('error', 'Please verify postcode is correct');
        }

        $result = \DB::select("SELECT
                        route_id, MIN(
                          3959 * acos (
                          cos ( radians(".$location->lat.") )
                          * cos( radians( lat ) )
                          * cos( radians( lng ) - radians(".$location->lng.") )
                          + sin ( radians(".$location->lat.") )
                          * sin( radians( lat ) )
                        )
                    ) AS distance
                    FROM route_points
                    GROUP BY route_id
                    HAVING distance < ".$distance."
                    ORDER BY distance");

        foreach ($result as $value) {
          $ids[] = $value->route_id;
        }

        if (empty($ids)){
          return redirect('/')->with('error', 'There are no routes matching this criteria');
        }

        //return redirect()->route('route', ['id' => $ids[array_rand($ids)]]);
        return redirect('route/'. $ids[array_rand($ids)]);
      }else{
        //No postcode has been entered

      }
      return view('index');
    }
}
