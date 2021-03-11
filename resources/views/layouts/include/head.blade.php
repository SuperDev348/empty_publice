<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <?php
    if (DB::table('settings')->where('setting_name', '=', 'app_name')->exists()) {
        $app_name = DB::table('settings')->where('setting_name', 'app_name')->select('setting_value')->first()->setting_value;
    } else {
        $app_name = "";
    }
    ?>

    @if(!$app_name)
        <title>@yield('title')</title>
    @else
        <title>@yield('title') {{$app_name}}</title>
    @endif

    <!-- Styles -->
    <?php
    $appDetails = config('gain');
    $cssLinks = [
        [
            'comment' => '<!--Import favicon-->',
            'assets' => asset('images/favicon/favicon.png'),
            'rel' => 'shortcut icon',
            'media' => '',
        ],
        [
            'comment' => '<!--summernote-lite.css-->',
            'assets' => asset('summernote-0.8.9/summernote-lite.css'),
            'rel' => 'stylesheet',
            'media' => '',
        ],
        [
            'comment' => '<!--line-awesome.css-->',
            'assets' => asset('line-awesome/css/line-awesome.min.css'),
            'rel' => 'stylesheet',
            'media' => '',
        ],
        [
            'comment' => '<!--Import style.css-->',
            'assets' => asset('css/main.css'),
            'rel' => 'stylesheet',
            'media' => '',
        ],
        [
            'comment' => '<!--Import style.css-->',
            'assets' => asset('css/category-divs.css'),
            'rel' => 'stylesheet',
            'media' => '',
        ],
        [
            'comment' => '<!--Import style.css-->',
            'assets' => asset('css/style.css'),
            'rel' => 'stylesheet',
            'media' => '',
        ],
        [
            'comment' => '<!--Import animate.css-->',
            'assets' => asset('css/animate.css'),
            'rel' => 'stylesheet',
            'media' => '',
        ],
        [
            'comment' => '<!--Import slick.css-->',
            'assets' => asset('css/slick.css'),
            'rel' => 'stylesheet',
            'media' => '',
        ],
        [   
            'comment' =>  '<!-- Import Glyphicon -->',
            'assets' => asset('flags/css/flag-icon.css'),
            'rel' => 'stylesheet',
            'media' => '',
        ],
        [
            'comment' => '<!-- Font Awesome Icons -->',
            'assets' => 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
            'rel' => 'stylesheet',
            'media' => '',
        ]

    ];

    foreach ($cssLinks as $cssLink) {
        if ($cssLink['comment']) {
            echo $cssLink['comment'] . "\n";
        }

        if ($cssLink['media']) {
            echo "<link href='" . $cssLink['assets'] . '?app_version=' . $appDetails['app_version'] . "' rel='" . $cssLink['rel'] . "' media='" . $cssLink['media'] . "'>\n";
        } else {
            echo "<link href='" . $cssLink['assets'] . '?app_version=' . $appDetails['app_version'] . "' rel='" . $cssLink['rel'] . "'>\n";
        }
    }
    ?>    
<style>
.modal-backdrop {
    display:none !important;
}
</style>
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
