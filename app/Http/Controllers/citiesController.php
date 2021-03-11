<?php

namespace App\Http\Controllers;
use App\Cities;
use Illuminate\Http\Request;

class citiesController extends Controller
{
    public function getCities()
    {
        $data = Cities::all();

        return $data;
    }
}
