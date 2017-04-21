<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Route;
use App\RoutePoint;

class RouteController extends Controller
{
    function show($id){

      $route = Route::get()->where('id', '=', $id);
      $points = RoutePoint::get()->where('route_id', '=', $id);

      return view('index', compact('route', 'points'));
    }

    function index(){
      return view('index');
    }
}
