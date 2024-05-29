<?php

use App\Http\Controllers\AirtableSyncController;
use App\Http\Controllers\Authenticaion\AuthController;
use App\Http\Controllers\Authenticaion\LoginController;
use App\Http\Controllers\Backend\Booking\BookingController;
use App\Http\Controllers\Backend\Package\PackageSettingController;
use App\Http\Controllers\Backend\Price\CarTypeWisePriceController;
use App\Http\Controllers\Backend\Setting\FavouriteRouteController;
use App\Http\Controllers\Backend\Setting\MailSettingsController;
use App\Http\Controllers\Backend\User\PermissionController;
use App\Http\Controllers\Backend\User\RoleController;
use App\Http\Controllers\Backend\User\UserController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\FareSettingsController;
use App\Http\Controllers\GeneralFareSettingsController;
use App\Http\Controllers\GoogleApiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentSettingController;
use App\Http\Controllers\SslCommerzPaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [LoginController::class, 'login']);
    Route::get('/car-type-wise-prices/km-wise', [CarTypeWisePriceController::class, 'kmWisePrice']);
    Route::post('/trip-prices', [CarTypeWisePriceController::class, 'tripPrices']);
    Route::get('package-settings', [PackageSettingController::class, 'index'])->name('getPackages');
    Route::get('package-settings/{id}', [PackageSettingController::class, 'find'])->name('getPackage');
    Route::get('otp-form',[LoginController::class,'otpForm']);
    Route::POST('get-otp', [LoginController::class, 'requestOtp'])->name('request-otp');
    Route::POST('verify-otp', [LoginController::class, 'submitOtp'])->name('verifyOtp');

    Route::group(['prefix' => 'google-api'], function () {
        Route::get('/place-details', [GoogleApiController::class, 'placeDetails']);
        Route::get('/distance', [GoogleApiController::class, 'distance']);
        Route::get('/query-autocomplete', [GoogleApiController::class, 'queryAutoComplete']);
    });
    //campaign apis
    Route::group(['prefix' => 'campaign'], function () {
        Route::get('/', [CampaignController::class, 'index']);
        Route::post('/log', [CampaignController::class, 'campaignLogStore']);
    });
    //campaign api end
    //payment routes from previous website
    Route::get('/booking/payment/{bookingId}', [PaymentController::class, 'makePayment']);
    Route::get('/invoice/{id}', [PaymentController::class, 'invoiceDetails'])->name('makePayment');
    Route::get('/makePayment', [SslCommerzPaymentController::class, 'customPayment'])->name('makePayment.store');
    Route::post('/success', [SslCommerzPaymentController::class,'success']);
    Route::post('/fail', [SslCommerzPaymentController::class],'fail');
    Route::post('/cancel', [SslCommerzPaymentController::class],'cancel');
    Route::post('/ipn', [SslCommerzPaymentController::class],'ipn');
    Route::get('successful-payment-page', [PaymentController::class, 'paymentSuccessPage'])->name('paymentSuccessPage');
    //payment routes from previous website end



    Route::group(['prefix' => 'campaign'], function () {
        Route::get('/', [CampaignController::class, 'index']);
        Route::post('/log', [CampaignController::class, 'campaignLogStore']);
    });
    Route::group(['middleware' => ['tokenVerify']],function (){
        Route::post('/booking-request/create', [BookingController::class, 'create']);
        Route::get('customer-profile',[LoginController::class,'customerProfile']);
        Route::post('profile-update', [LoginController::class, 'profileUpdate'])->name('updateProfile');
        Route::get('referral-code', [LoginController::class, 'referralCode']);
        Route::group(['prefix' => 'customer'], function(){
            Route::get('booking/{id}', [BookingController::class, 'customerTripDetails']);
            Route::get('booking-history',[BookingController::class,'customerTripHistory']);
            Route::post('trip-reorder',[BookingController::class,'tripReorder']);
            Route::post('booking-update',[BookingController::class,'customerBookingUpdate']);
            Route::get('latest-trips',[BookingController::class, 'customerLatestTrips']);
        });
        Route::group(['prefix' => 'fav-routes'], function(){
            Route::get('/',[FavouriteRouteController::class,'usersFavouriteRoutes']);
            Route::post('/create',[FavouriteRouteController::class,'favRouteCreate']);
            Route::post('/update',[FavouriteRouteController::class,'favRouteUpdate']);
        });
        Route::group(['prefix' => 'fare'], function(){
            Route::get('/settings', [PaymentController::class, 'paymentSettings']);
            Route::get('/breakdown',[PaymentController::class, 'fareBreakDown']);
        });
        Route::post('campaign/log-update', [CampaignController::class, 'campaignLogUpdate']);
    });
    // authorized with operational route
    Route::group(['middleware' => ['auth']], function () {
        Route::apiResource('users', UserController::class);
        Route::get('users/{id}/permissions', [UserController::class, 'permissionsByUserId'])->name('permissionsByUserId');
        Route::put('update/permissions/user/{id}', [UserController::class, 'updatePermissionByUser'])->name('updatePermissionByUser');
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('permissions', PermissionController::class);
        Route::post('role-create', [RoleController::class,'store']);
        Route::post('customer-role-assign', [RoleController::class,'customerRoleAssign']);
        Route::group(['prefix' => 'search', 'as' => 'search.'], function () {
            Route::get('/users', [UserController::class, 'userFromUserService'])->name('userFromUserService');
        });
        Route::group(['prefix' => 'car-type-wise-prices'], function () {
            Route::get('/', [CarTypeWisePriceController::class, 'index'])->name('getCarTypeWisePrice');
            Route::post('/create', [CarTypeWisePriceController::class, 'store'])->name('storeCarTypeWisePrice');
            Route::post('/update', [CarTypeWisePriceController::class, 'update'])->name('updateCarTypeWisePrice');
            Route::get('/find', [CarTypeWisePriceController::class, 'find'])->name('findCarTypeWisePrice');
        });

        Route::group(['prefix' => 'package-settings'], function () {
            Route::post('/create', [PackageSettingController::class, 'store'])->name('packageCreate');
            Route::post('/update', [PackageSettingController::class, 'update'])->name('packageUpdate');
        });

        Route::get('/booking-requests', [BookingController::class, 'index']);
        Route::get('/booking-requests/m-team', [BookingController::class, 'bookingDataForMarketingTeam']);
        Route::get('/booking-request', [BookingController::class, 'getBookingByID']);
        Route::get('/bookings-for-excel', [BookingController::class, 'getBookingsForExcel']);
        Route::get('/booking-status-types', [BookingController::class, 'bookingStatusTypes']);
        Route::get('/payment-status-types', [BookingController::class, 'paymentStatusTypes']);
        Route::get('/trip-statistics', [BookingController::class, 'tripStatistics']);
        Route::post('/airtable-sync', [AirtableSyncController::class, 'sync']);

        Route::group(['prefix' => 'booking-update'], function () {
            Route::post('/user-info', [BookingController::class, 'userInfoUpdate']);
            Route::post('/booking', [BookingController::class, 'bookingInfoUpdate']);
            Route::post('/location', [BookingController::class, 'locationInfoUpdate']);
            Route::post('/driver', [BookingController::class, 'driverInfoUpdate']);
        });
        Route::get('/booking-payment/list',   [PaymentController::class, 'index']);
        Route::post('/booking-payment/create', [PaymentController::class, 'addPayment']);
        Route::post('/booking-payment/update', [PaymentController::class, 'updatePayment']);
        Route::post('/payment-mail', [PaymentController::class, 'paymentMail']);
        Route::group(['prefix' => 'campaign'], function () {
            Route::post('/', [CampaignController::class, 'campaignCreate']);
            Route::get('/logs', [CampaignController::class, 'getCampaignLogs']);
            Route::post('/update', [CampaignController::class, 'campaignUpdate']);
        });
        Route::get('/all-fav-routes',[FavouriteRouteController::class,'index']);
        Route::post('/refund-request', [BookingController::class, 'refundRequest']);

        Route::group(['prefix' => 'payment-settings'], function () {
            Route::get('/', [PaymentSettingController::class, 'index']);
            Route::post('/', [PaymentSettingController::class, 'store']);
            Route::post('/{id}', [PaymentSettingController::class, 'update']);
        });

        Route::group(['prefix' => 'mail-settings'], function () {
            Route::get('/', [MailSettingsController::class, 'index']);
            Route::post('/', [MailSettingsController::class, 'store']);
            Route::post('/{id}', [MailSettingsController::class, 'update']);
            Route::get('/{id}', [MailSettingsController::class, 'show']);
        });

        Route::group(['prefix' => 'fare-settings'], function () {
            Route::get('/', [FareSettingsController::class, 'index']);
            Route::post('/', [FareSettingsController::class, 'store']);
            Route::post('/{id}', [FareSettingsController::class, 'update']);
            Route::get('/{id}', [FareSettingsController::class, 'show']);
        });
        Route::group(['prefix' => 'multiplier-value'], function () {
            Route::post('/', [FareSettingsController::class, 'multiplierValueCreate']);
            Route::post('/{id}', [FareSettingsController::class, 'multiplierValueUpdate']);
            Route::delete('/{id}', [FareSettingsController::class, 'delete']);
        });
        Route::group(['prefix' => 'general-fare-settings'], function () {
            Route::get('/', [GeneralFareSettingsController::class, 'index']);
            Route::post('/', [GeneralFareSettingsController::class, 'store']);
            Route::post('/{id}', [GeneralFareSettingsController::class, 'update']);
            Route::get('/{id}', [GeneralFareSettingsController::class, 'show']);
        });
        Route::get('/mail-types', [MailSettingsController::class, 'getMailTypes']);
        Route::get('/mail/driver-info/{id}',[BookingController::class, 'driverInfoMail']);
    });
});
