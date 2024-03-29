@include('layouts.include.head')
<body>
@inject('appConfig', 'App\Http\Controllers\Controller')
@inject('basicData', 'App\Http\Controllers\API\SettingController')
<div id="app">

    @include('layouts.include.navbar')
    @include('layouts.include.sidebar')


    <main id="app">

        @yield('content')

    </main>
    
</div>
<?php
    $gain = config('gain');
    $appVersion = $gain['app_version'];
?>
<script>
    window.appConfig = {
        appUrl: "{{ $appConfig->appUrl }}",
        publicPath: " {{ $appConfig->publicPath }}",
        dateTimeFormat: "{{ $basicData->dateTimeFormat() }}",
        dateFormat: "{{ $basicData->dateFormat() }} ",
        knowDefaultRowSettings: "{{ $basicData->knowDefaultRowSettings() }}",
        currentUserId: "{{  $basicData->currentUserId() }}",
        currencySymbol: "{{ $appConfig->currency_symbol }}",
        currencyFormat: "{{ $appConfig->currency_format }}",
        thousandSeparator: "{{ $appConfig->thousand_separator }}",
        decimalSeparator: "{{ $appConfig->decimal_separator }}",
        numberOfDecimal: "{{ $appConfig->number_of_decimal }}>",
        appLogo: "{{ $appConfig->app_logo }}",
        appName: "{{  $appConfig->app_name }} ",
        currencyCode: "{{ $appConfig->currency_code }}",
        timeFormat: "{{ $appConfig->time_format }}",
        businessType: "{{ $appConfig->business_type }}",
        appVersion: "{{ $appVersion}}",

    }
</script>
@include('layouts.include.footer')