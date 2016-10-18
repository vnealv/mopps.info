<?php
	
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

	$api->post('auth/login', 'App\Api\V1\Controllers\AuthController@login');
	$api->post('auth/signup', 'App\Api\V1\Controllers\AuthController@signup');
	$api->post('auth/recovery', 'App\Api\V1\Controllers\AuthController@recovery');
	$api->post('auth/reset', 'App\Api\V1\Controllers\AuthController@reset');

//    $api->get('auth/simple', 'App\Api\V1\Controllers\AuthController@simple');
//
//	// example of protected route
//	$api->get('protected', ['middleware' => ['api.auth'], function () {
//		return \App\User::all();
//    }]);
//
//    $api->post('protectedp', ['middleware' => ['api.auth'], function () {
//        return \App\User::all();
//    }]);
//
//	// example of free route
//	$api->get('free', function() {
//		return \App\User::all();
////        return response()->error("SS");
//	});

});

$api->version('v1', ['middleware' => 'api.auth'], function ($api) {
    //PROFILE
    $api->post('profile', 'App\Api\V1\Controllers\ProfileController@show');
    $api->post('profile/update', 'App\Api\V1\Controllers\ProfileController@update');
//    $api->post('profile/destroy', 'App\Api\V1\Controllers\ProfileController@destroy');

    //ACCOUNT
    $api->post('account', 'App\Api\V1\Controllers\AccountController@show');
    $api->post('account/add', 'App\Api\V1\Controllers\AccountController@add');

    //VEHICLE
    $api->post('vehicle', 'App\Api\V1\Controllers\VehicleController@show');
    $api->post('vehicle/add', 'App\Api\V1\Controllers\VehicleController@add');
    $api->post('vehicle/update', 'App\Api\V1\Controllers\VehicleController@update');
    $api->post('vehicle/delete', 'App\Api\V1\Controllers\VehicleController@delete');

    //PARKING (PSession)
    $api->post('psession/add', 'App\Api\V1\Controllers\ParkingSessionController@add');
    $api->post('psession/active', 'App\Api\V1\Controllers\ParkingSessionController@active_session');

    $api->post('parking', 'App\Api\V1\Controllers\ParkingSessionController@parkingList');

    $api->post('history', 'App\Api\V1\Controllers\HistoryController@history');

    $api->post('photo/user', 'App\Api\V1\Controllers\PhotoController@add_user_photo');
    $api->post('photo/vehicle', 'App\Api\V1\Controllers\PhotoController@add_vehicle_photo');
});
