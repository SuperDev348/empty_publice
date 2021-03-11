<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\Permissions;
use Illuminate\Support\Facades\Lang;
use App\Models\SocialLink;
use DB;

class SocialLinkController extends Controller
{

    public function permissionCheck()
    {
        $controller = new Permissions;
        return $controller;
    }

    public function index()
    {
        $social = SocialLink::get();
        return array_pluck($social->toArray(), 'social_link_name', 'social_link_value');

    }

    public function update(Request $request)
    {
        SocialLink::where('social_link_value', 'facebook')->update(['social_link_name' => $request->facebook]);
        SocialLink::where('social_link_value', 'instagram')->update(['social_link_name' => $request->instagram]);
        SocialLink::where('social_link_value', 'linkedin')->update(['social_link_name' => $request->linkedin]);
        SocialLink::where('social_link_value', 'twitter')->update(['social_link_name' => $request->twitter]);
        SocialLink::where('social_link_value', 'google_plus')->update(['social_link_name' => $request->google_plus]);
        SocialLink::where('social_link_value', 'youtube')->update(['social_link_name' => $request->youtube]);
        SocialLink::where('social_link_value', 'pinterest')->update(['social_link_name' => $request->pinterest]);
        SocialLink::where('social_link_value', 'tumblr')->update(['social_link_name' => $request->tumblr]);
        SocialLink::where('social_link_value', 'flickr')->update(['social_link_name' => $request->flickr]);
        $response = [
            'message' => Lang::get('lang.social_link_update')
        ];
        return response()->json($response, 201);
    }


    public function getSocialData()
    {
        $frontsocial = SocialLink::get();
        return array_pluck($frontsocial->toArray(), 'social_link_name', 'social_link_value');
    }
}
