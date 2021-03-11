<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Session;
use App;
use Config;

class Language
{
    public function handle($request, Closure $next)
    {
        $installCheck = config('gain.installed');
        
        if($installCheck){
            $language = Setting::getSettingValue('language_setting');
         
            $locale =  session('my_locale', config('app.locale'));
            
            //$locale = $language->setting_value;
        }else{
           
            $locale = "english";
        }
        
        App::setLocale($locale);

        return $next($request);
    }
}