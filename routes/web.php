<?php


use App\Http\Controllers\API\SmsTemplateController;

Route::get('/', 'HomeController@homePage1')->name('home');
Route::get('/book/{id}', 'HomeController@homePage')->name('welcome');
Route::get('/demo', 'HomeController@chooseDemoPage');
Route::post('/update-business-type', 'HomeController@updateBusinessType');
Route::get('services/{alias}', 'HomeController@homePage');
Route::post('contact-us', 'HomeController@contactUs');
Route::get('get-contact-info', 'API\ContactInformationController@getContactInfo');
Route::post('contact-info-update/{contactInfo}', 'API\ContactInformationController@contactInfoUpdate');

Route::get('/login', '\App\Http\Controllers\Auth\LoginController@showLoginForm');
Route::post('/login', '\App\Http\Controllers\Auth\LoginController@login');

// Auth Route
Route::post('login', ['as' => 'login', 'uses' => '\App\Http\Controllers\Auth\LoginController@login']);

//Route::get('/sms', 'Auth\RegisterController@sendsms');
Route::post('/recover', 'AuthController@recover');

Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequest');
Route::post('/recoverpass', 'AuthController@recover');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@resetForm');

Route::post('/password/reset', 'AuthController@reset');

Route::get('/verify/{token}', 'Auth\RegisterController@verifyUser');
Route::get('accept/{token}', 'API\InviteController@accept');
Route::get('register/{token}', 'Auth\RegisterController@regForm');
Route::post('register/{token}', 'Auth\RegisterController@invitedRegistration');
Route::get('register', 'Auth\RegisterController@signup');
Route::post('registerclient', 'Auth\RegisterController@register');
Route::post('/setcookie', 'HomeController@setCookie');
Route::get('/client-custom-field', 'API\CustomFieldController@getClientBookingField');
Route::post('sms-confirmation/{token}', 'Auth\RegisterController@smsConfirmationRegistration');
Route::post('resend-sms-confirmation', 'Auth\RegisterController@resendVerificationCode');
Route::get('/get-sms-setting-status', 'API\SettingController@getSmsSettingStatus');


//---------------------------------------------------------------------------
//category
Route::get('/getAllCategory', 'CategoryController@getallCategory');

//vendors
Route::get('/getvendorsByCategory/{id}', 'VendorsController@getvendorsByCategory');
Route::get('/getallvendors', 'VendorsController@getallvendors');
Route::get('/vendors/getVendor/{id}', 'VendorsController@getVendor');

//services
Route::get('/getAllServiceFormDataByVendor/{id}', 'API\ServiceController@getServiceListDataByVendor');


//product
Route::get('/getAllProductsFormDataByVendor/{id}', 'ProductController@getProductsListDataByVendor');
Route::get('/getproduct/{id}', 'ProductController@show');


Route::get('/lang/{key}', function ($key) {
    App::setLocale($key);
    Cache::flush();
   
});
Route::post('/locale', function(){
    Cache::flush();
    session(['my_locale' => Request::Input('locale')]);

    return redirect()->back();
});

//Banners
Route::post('/getallactivebanner', 'bannersController@getallActiveBanner');

//Cities
Route::get('/getCities', 'citiesController@getCities');

//---------------------------------------------------------------------------





// Auth middleware group Route
Route::group(['middleware' => 'auth'], function () {
    //vendors ---------------------------------------------------------
    Route::post('/vendors/addVendor/', 'VendorsController@addVendor')->middleware('permissions:can_see_vendors');
    Route::post('/vendors/updateVendor/{id}', 'VendorsController@updateVendor')->middleware('permissions:can_see_vendors');

    Route::post('/vendorslist', 'VendorsController@getData');
    Route::get('/vendors', 'VendorsController@vendorsIndex')->middleware('permissions:can_see_vendors');
    Route::get('/vendors/allVendorsid', 'VendorsController@allVendorsid');
    Route::post('/vendorOffdays', 'VendorsController@vendorOffdays')->middleware('permissions:can_edit_off_day_setting');
    Route::get('/vendorOffdaysData', 'VendorsController@vendorOffdaysData')->middleware('permissions:can_edit_off_day_setting');
    Route::get('/vendorprofileData', 'VendorsController@vendorprofileData')->middleware('permissions:can_edit_off_day_setting');
    Route::post('/updateMyProfile', 'VendorsController@updateMyProfile')->middleware('permissions:can_edit_off_day_setting');




    //category
    Route::Post('/categorylist', 'CategoryController@getCategoryList')->middleware('permissions:roles');
    Route::Post('/createcategory', 'CategoryController@store')->middleware('permissions:roles');
    Route::Post('/updatecategory/{id}', 'CategoryController@edit')->middleware('permissions:roles');
    Route::get('/getcategory/{id}', 'CategoryController@show');



    //products
    Route::get('/products', 'ProductController@index')->middleware('permissions:can_add_service');
    Route::post('/addproduct', 'ProductController@store')->middleware('permissions:can_add_service');
    Route::post('/editproduct/{id}', 'ProductController@edit')->middleware('permissions:can_add_service');
    Route::post('productslist', 'ProductController@getData')->middleware('permissions:can_add_service');

    //order products
    Route::get('/orders', 'ProductOrdersController@index')->middleware('permissions:can_add_service');
    Route::post('/orderPrduct', 'ProductOrdersController@store');
    Route::post('ProductsOrderlist', 'ProductOrdersController@getData')->middleware('permissions:can_add_service');
    Route::post('/actionOrder/{id}', 'ProductOrdersController@update')->middleware('permissions:can_manage_booking');

    //banners
    Route::get('/banners', 'bannersController@Index')->middleware('permissions:can_see_vendors');
    Route::get('/getbanner/{id}', 'bannersController@getBanner')->middleware('permissions:can_see_vendors');
    Route::post('/bannerslist', 'bannersController@getData')->middleware('permissions:can_see_vendors');
    Route::post('/addbanner', 'bannersController@store')->middleware('permissions:can_see_vendors');
    Route::post('/updatebanner/{id}', 'bannersController@edit')->middleware('permissions:can_see_vendors');
    Route::post('/deletebanner/{id}', 'bannersController@delete')->middleware('permissions:can_see_vendors');

    //---------------------------------------------------------------
    Route::get('/dashboard', 'API\DashboardController@index')->name('dashboard');
    Route::get('/dashboarddata', 'API\DashboardController@getData');

    // Profile Route
    Route::get('myprofile', 'API\ProfileController@myProfile');
    Route::get('myprofile/calender', 'API\ProfileController@myProfileCalender');
    Route::get('user-profile', 'API\ProfileController@index');
    Route::post('profile/{id}', 'API\ProfileController@update');
    Route::post('/updatePassword/{id}', 'API\ProfileController@updatepassword');
    Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
    Route::post('save-google-calendar', 'API\GoogleCalendarController@saveGoogleCalendar');
    Route::get('get-calender-data', 'API\GoogleCalendarController@getGoogleCalendar');
    Route::get('get-auth-code', 'API\GoogleCalendarController@getAuthCode');


    // Custom Field routes

    Route::post('service-custom-field', 'API\CustomFieldController@serviceFieldData');
    Route::post('booking-custom-field', 'API\CustomFieldController@bookingFieldData');
    Route::post('save-custom-field', 'API\CustomFieldController@save');
    Route::post('save-custom-field/{id}', 'API\CustomFieldController@saveFields');
    Route::post('/get-service-field', 'API\CustomFieldController@getServiceData');
    Route::post('/get-booking-field', 'API\CustomFieldController@getBookingData');
    Route::get('get-custom-field/{id}', 'API\CustomFieldController@getCustomData');
    Route::post('/get-service-custom-field', 'API\CustomFieldController@getServiceField');

    // Setting Service Delete
    Route::get('/customfiledservice-edit/{id}', 'API\CustomFieldController@serviceFiledEdit');
    Route::post('/customfiled-update/{id}', 'API\CustomFieldController@update');
    Route::post('/customfiled-service-delete/{id}', 'API\CustomFieldController@deleteSettingservice');
    Route::get('/customfiledbooking-edit/{id}', 'API\CustomFieldController@bookingFiledEdit');
    Route::post('/customfiledbooking-delete/{id}', 'API\CustomFieldController@deleteSettingbooking');


    // Email template Route
    Route::post('templatelist', 'API\EmailTemplateController@templateList');
    Route::get('/gettemplatecontent/{id}', 'API\EmailTemplateController@show')->middleware('permissions:can_edit_email_template');
    Route::post('/setcustomcontent/{id}', 'API\EmailTemplateController@update')->middleware('permissions:can_edit_email_template');

    // Email Setting Route
    Route::post('/emailsetting', 'API\SettingController@emailSettingSave')->middleware('permissions:can_edit_email_setting');
    Route::get('/emailsettingdata', 'API\SettingController@emailSettingData')->middleware('permissions:can_edit_email_setting');
    Route::post('/testmail', 'API\SettingController@testMail');

    // Service Route
    Route::post('/serviceid/{id}', 'API\ServiceController@update')->middleware('permissions:can_add_service');
    Route::post('/serviceSetting/{id}', 'API\ServiceController@serviceSetting')->middleware('permissions:can_add_service');
    Route::post('/servicedeactive/{id}', 'API\ServiceController@deactivate')->middleware('permissions:can_add_service');
    Route::post('/servicedefault/{id}', 'API\ServiceController@setdefault')->middleware('permissions:can_add_service');
    Route::post('servicelist', 'API\ServiceController@getData')->middleware('permissions:can_add_service');
    Route::post('serviceshow', 'API\ServiceController@getSortedData')->middleware('permissions:can_add_service');
    Route::post('/deleteservice/{id}', 'API\ServiceController@delete')->middleware('permissions:can_add_service');
    Route::get('servicenew/create', 'API\ServiceController@create')->middleware('permissions:can_add_service');
    Route::post('servicenew/create/store', 'API\ServiceController@store')->middleware('permissions:can_add_service');
    Route::get('service-userlist', 'API\ServiceController@serviceUserlist')->middleware('permissions:can_add_service');

    //Worker Route
    Route::post('workerlist', 'API\WorkerController@getData')->middleware('permissions:can_add_service');
    Route::post('/workerid/{id}', 'API\WorkerController@update')->middleware('permissions:can_add_service');
    Route::post('/workernew/create/store', 'API\WorkerController@store')->middleware('permissions:can_add_service');
    Route::get('/get-worker/{id}','API\WorkerController@getWorkerData');
    Route::get('/get_all_services/{id}','API\WorkerController@getAllSerivceData');
    Route::get('/worker-serviceid/{id}','API\WorkerController@getWorkerAllSerivceData');
    Route::post('/workerServiceSetting/{id}','API\WorkerController@saveWorkerAllSerivceData');
    Route::post('/deleteworker/{id}', 'API\WorkerController@delete')->middleware('permissions:can_add_service');


    // Service policy Route
    Route::get('servicepolicy', 'API\ServicePolicyController@index');
    Route::post('servicepolicy-store', 'API\ServicePolicyController@store')->middleware('permissions:can_add_service_policy');
    Route::get('servicepolicy-edit/{servicePolicy}', 'API\ServicePolicyController@edit')->middleware('permissions:can_edit_service_policy');
    Route::post('servicepolicy-update/{servicePolicy}', 'API\ServicePolicyController@update')->middleware('permissions:can_update_service_policy');
    Route::post('servicepolicy-delete/{servicePolicy}', 'API\ServicePolicyController@destroy')->middleware('permissions:can_delete_service_policy');

    // Social Link Route

    Route::get('social-link', 'API\SocialLinkController@index')->middleware('permissions:can_manage_social_link');
    Route::post('social-link-update', 'API\SocialLinkController@update')->middleware('permissions:can_manage_social_link');

    // Off Day Setting Route
    Route::post('/offdaysetting', 'API\SettingController@offdays')->middleware('permissions:can_edit_off_day_setting');
    Route::get('/offdaysdata', 'API\SettingController@offdaysData')->middleware('permissions:can_edit_off_day_setting');

    // Holiday Route
    Route::get('/holiday', 'API\HolidayController@index')->middleware('permissions:can_edit_holi_day_setting');
    Route::post('/holiday-store', 'API\HolidayController@store')->middleware('permissions:can_edit_holi_day_setting');
    Route::get('/holidays/{id}', 'API\HolidayController@show')->middleware('permissions:can_edit_holi_day_setting');
    Route::post('/holiday-update/{id}', 'API\HolidayController@updateHolidays')->middleware('permissions:can_edit_holi_day_setting');
    Route::post('/holiday-delete/{id}', 'API\HolidayController@destroy')->middleware('permissions:can_edit_holi_day_setting');
    Route::post('getholidays', 'API\HolidayController@sortedList')->middleware('permissions:can_edit_holi_day_setting');

    // View Route
    Route::get('/settings', 'API\SettingController@index');
    Route::get('/bookings', 'API\BookingController@bookingIndex')->middleware('permissions:can_manage_booking');
    Route::post('/deletebooking/{id}', 'API\BookingController@delete')->middleware('permissions:can_manage_booking');
    Route::get('/services', 'API\ServiceController@services')->middleware('permissions:can_add_service');
    Route::get('/workers', 'API\WorkerController@workers')->middleware('permissions:can_add_service');
    
    
    //client bookings
    Route::get('/bookingsclient', 'API\BookingController@bookingIndex');
    Route::post('/bookinglistclient', 'API\ClientDashboardController@myprofile');

    // App Setting Route
    Route::post('/basic-setting', 'API\SettingController@basicSettingSave')->middleware('permissions:can_manage_application_setting');;
    Route::get('/basicsettingdata', 'API\SettingController@basicSettingData');
    Route::get('/timezone', 'API\SettingController@getTimezone');
    Route::get('/knowDefaultRowSettings', 'API\SettingController@knowDefaultRowSettings');

    Route::get('/dateformat', 'API\SettingController@dateFormat');
    // Invite Route
    Route::post('/invite', 'API\InviteController@process')->middleware('permissions:invite');
    Route::get('/allroleid', 'API\InviteController@getRoleId');

    // Booking Route
    Route::get('/allbooking', 'API\BookingController@allBookins');
    Route::get('/inovoice-pdf/{id}', 'API\BookingController@generateInvoice');
    Route::post('/bookingshow', 'API\BookingController@getBookingList');
    Route::post('/actionbooking/{id}', 'API\BookingController@action')->middleware('permissions:can_manage_booking');
    Route::get('/booking-payment/{id}', 'API\BookingController@bookingPaymentDetails')->middleware('permissions:can_manage_booking');

    // Role Route
    Route::post('/roletitle', 'API\RoleController@allData');
    Route::get('/roletitleuser', 'API\RoleController@allDataUser');
    Route::post('/addrole', 'API\RoleController@store')->middleware('permissions:roles');
    Route::post('/addrole/{id}', 'API\RoleController@update')->middleware('permissions:roles');
    Route::get('/rolepermissions/{id}', 'API\RoleController@getRolePermissions');
    Route::get('/rolepermissions/', 'API\RoleController@getRoleWithout');
    Route::post('/deleterole/{id}', 'API\RoleController@delete')->middleware('permissions:roles');

    // Notification Route
    Route::get('notify', 'API\NotificationController@index');
    Route::post('/upnotify/{id}', 'API\NotificationController@update');
    Route::post('countup/{id}', 'API\NotificationController@countUp');
    Route::get('notification', 'API\NotificationController@allNotification');

    //all users
    Route::post('/roleassign/{id}', 'API\RoleAssignController@update')->middleware('permissions:roles');
    Route::post('userlist', 'API\UserController@getUserList')->middleware('permissions:roles');
    Route::get('/user/{id}', 'API\UserController@userDetails');
    Route::get('/user-role/{id}', 'API\UserController@getUserWithRole')->name('users.role');
    Route::get('/user-vendor/{id}', 'API\UserController@getUserWithVendor');
    Route::post('/disableEnableUser/{id}', 'API\UserController@disableEnableUser')->middleware('permissions:roles');
    Route::post('/user-delete/{id}', 'API\UserController@delete')->middleware('permissions:roles');
    Route::post('/user-bookings/{id}', 'API\UserController@userBookingList')->middleware('permissions:can_manage_user_setting');


    // Client setting
    Route::get('clientsetting', 'API\SettingController@clientSetting');
    Route::post('clientsetting', 'API\SettingController@updateClientSetting')->middleware('permissions:can_manage_client_setting');
    Route::get('/clients', 'API\ClientController@index')->middleware('permissions:can_manage_client_setting');
    Route::post('clientlist', 'API\ClientController@allClients')->middleware('permissions:can_manage_client_setting');
    Route::post('/client-update/{id}', 'API\ClientController@updateClient')->middleware('permissions:can_manage_client_setting');
    Route::get('/client-edit/{id}', 'API\ClientController@edit')->middleware('permissions:can_manage_client_setting');
    Route::post('/deleteclient/{id}', 'API\ClientController@delete')->middleware('permissions:can_manage_client_setting');
    Route::get('/client/{id}', 'API\ClientController@show')->middleware('permissions:can_manage_client_setting');
    Route::post('/client-bookings/{id}', 'API\ClientController@clientBookingList')->middleware('permissions:can_manage_client_setting');
    Route::post('/dashboard-client-bookings', 'API\ClientDashboardController@dashboardClientBookingList');
    Route::match(array('GET', 'POST'), '/dashboard-client-bookings', 'API\ClientDashboardController@dashboardClientBookingList');
    Route::get('/clients/{id}', 'API\ClientController@userDetails')->middleware('permissions:can_manage_client_setting');
    Route::get('/booking-details/{id}/{flag?}', 'API\BookingController@bookingDetails')->middleware('permissions:can_manage_booking');
    Route::get('/clientdashboarddata', 'API\ClientDashboardController@getData');
    Route::post('/bookinglist', 'API\ClientDashboardController@getBookingData')->middleware('permissions:can_manage_booking');
    Route::get('/invoice-details/{id}', 'API\BookingController@invoiceDetails');

    // Report route
    Route::get('/reports', 'API\ReportController@index')->middleware('permissions:can_see_reports');
    Route::get('/booking-report/{id}/{date}', 'API\ReportController@getReport')->middleware('permissions:can_see_reports');
    Route::get('/getlangdir', 'API\LocalizationController@getLangDir');

    // Payments Route
    Route::get('/payments', 'API\PaymentController@paymentIndex');
    Route::post('/payments', 'API\PaymentController@getPaymentList');
    Route::post('/payment/store', 'API\PaymentController@store')->middleware('permissions:can_manage_payment_methods');
    Route::get('/payments/{id}', 'API\PaymentController@show')->middleware('permissions:can_manage_payment_methods');
    Route::post('/payments/{id}', 'API\PaymentController@updatePayment')->middleware('permissions:can_manage_payment_methods');
    Route::get('/payment/payment-details/{id}', 'API\PaymentController@duePayment');
    Route::post('/payment/payment-details', 'API\PaymentController@manualPayment')->middleware('permissions:can_manage_booking');
    Route::post('/payment-delete/{id}', 'API\PaymentController@destroy')->middleware('permissions:can_manage_payment_methods');
    Route::post('/manualpayment/{id}', 'API\PaymentController@manualPayment')->middleware('permissions:can_manage_booking');

    // Sms Setting Route

    Route::get('/get-sms-data', 'API\SettingController@getSmsData');
    Route::post('/sms-setting-update', 'API\SettingController@smsSettingUpdate')->middleware('permissions:can_edit_sms_settings');
    Route::post('/sms-template-list', [SmsTemplateController::class, 'index'])->name('templates');
    Route::get('/template/{id}', [SmsTemplateController::class, 'show'])->name('templates-data');
    Route::post('/template/{id}/update', [SmsTemplateController::class, 'update'])->name('templates-update');


    // Updates Route
    Route::get('/gain-update', 'API\UpdateController@applicationVersion');
    Route::get('/update-version-list', 'API\UpdateController@versionUpdateList');
    Route::post('/install-new-version/{version}', 'API\UpdateController@updateAction');
    Route::get('/update-list', 'API\UpdateController@updateAppUrl');
    Route::get('/curl_get_contents', 'API\UpdateController@curl_get_contents');
    Route::get('/nexInstallVersion', 'API\UpdateController@nexInstallVersion');

    // break time settings
    Route::post('/break-times', 'API\BreakTimeController@index');
    Route::post('/break-time/store', 'API\BreakTimeController@store');
    Route::post('/break-time/{id}', 'API\BreakTimeController@update');
    Route::get('/break-time/{id}', 'API\BreakTimeController@show');
    Route::get('/break-times', 'API\BreakTimeController@getAllData');
    Route::post('/break-time/{id}/delete', 'API\BreakTimeController@destroy');

});


Route::get('/clear-cache', function () {
    // Artisan::call('cache:clear');
    Cache::flush();

    return response()->json(['message' => \Illuminate\Support\Facades\Lang::get('lang.the_language_cache_has_been_removed')], 201);
});

Route::post('/paypal-transaction-complete', 'API\PaymentController@paypalPayment');

//service slot timings
Route::get('/servicetiming/{id}/{date}', 'API\ServiceController@getTiming');
Route::get('/servicetiming/{id}/', 'API\ServiceController@getTimingZero');
Route::get('serviceshowforbooking', 'API\ServiceController@getServiceAndOffDays');
Route::get('/servicelist', 'API\ServiceController@activeData');
Route::get('/get-service-workers/{vid}/{sid}','API\WorkerController@getWorkersByService');

//Route::get('/workerlist','API\WorkerController@activeData');

Route::get('/serviceid/{id}', 'API\ServiceController@show');
Route::get('/service-by-id/{id}', 'API\ServiceController@getServiceById');
Route::get('/bookservice/{id}', 'API\BookingController@index');
Route::get('/getcurrency', 'API\SettingController@getCurrency');
Route::post('/bookservice', 'API\BookingController@setBooking');
Route::get('/paymentstripe', 'API\PaymentController@paymentForm');
Route::post('/paymentstripe', 'API\PaymentController@paymentFunction');
Route::get('/getpmethods', 'API\PaymentController@getAllMethods');
Route::get('/single-book-service/{id}', 'API\BookingController@show');
Route::post('/update-booking/{id}', 'API\BookingController@update');

// salon service list
Route::get('/getAllServiceFormData', 'API\ServiceController@getServiceListData');
Route::post('/salon-service-book/{id}', 'API\BookingController@salonBooking');
Route::get('/getAllsocialData', 'API\SocialLinkController@getSocialData');
Route::get('getfrontservicepolicy', 'API\ServicePolicyController@getservicePolicy');

Route::get('/get-services/{id}', 'API\ServiceController@getServiceList');

//Worker 


// Localization
Route::get('/js/lang.js', function () {
    $strings = Cache::rememberForever('lang.js', function () {
        $lang = config('app.locale');
        $files = glob(resource_path('lang/' . $lang . '/*.php'));
        $strings = [];
        foreach ($files as $file) {
            $name = basename($file, '.php');
            if ($name !== "custom" && $name !== "default") {
                $new_keys = require $file;
                $strings = array_merge($strings, $new_keys);
            }
        }
        return $strings;
    });
    header('Content-Type: text/javascript');
    echo('window.i18n = ' . json_encode(array("lang" => $strings)) . ';');
    exit();
})->name('assets.lang');

// Route::fallback(function(){
//     return response()->json([
//         'message' => 'Page Not Found. If error persists, contact info@website.com'], 404);
// });