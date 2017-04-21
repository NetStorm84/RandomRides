<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Route;

class RouteController extends Controller
{
    function show($id){

      $route = Route::get()->where('id', '=', $id);

      return view('index', compact('route'));
    }
}
