<?php
/**
 * Created by PhpStorm.
 * User: neal
 * Date: 10/08/2016
 * Time: 5:33 PM
 */

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

use JWTAuth;
use Validator;
use App\ParkingSession;
use App\Parking;
use App\UserAccount;
use App\Vehicle;
use App\History;

use App\UserPhoto;
use App\VehiclePhoto;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Dingo\Api\Exception\ValidationHttpException;


/**
 * Photos.
 *
 * @Resource("Photo")
 */
class PhotoController extends Controller
{

    use Helpers;

    /**
     * Add/Update user photo`PROTECTED`
     *
     * Add OR update user photo with `token`, `base64`.
     *
     *
     * @Post("/api/photo/user")
     * @Versions({"v1"})
     *
     * @Request({
    "data" : {
    "base64" : "akjshdkjashdkjashdkjahsdkjh"
    }
    }, headers={"Authorization": "Bearer {token_goes_here}"})
     * @response(200, body={
    "message": "success",
    "errors": {},
    "status_code": 200,
    "fail": 0,
    "return": {
    "inserted": true
    }
    })
     */
    public function add_user_photo(Request $request){
        $currentUser = JWTAuth::parseToken()->authenticate();
        $credentials = $request->json()->get('data');


        //validate input
        $validator = Validator::make($credentials, [
            'base64' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        $credentials['photo_id'] = $this->get_guid();
        $credentials['user_id'] = $currentUser->id;

        UserPhoto::where('user_id', $currentUser->id)->delete();

        $photo = UserPhoto::create($credentials);

        if($photo){
            return array("inserted" => true);
        } else{
            return $this->response->errorInternal('Something Went Wrong!');
        }
    }


    /**
     * Add/Update vehicle photo`PROTECTED`
     *
     * Add OR update vehicle photo with `token`, `base64`, `vehicle_id` OR `vehicle_number`.
     *
     *
     * @Post("/api/photo/vehicle")
     * @Versions({"v1"})
     *
     * @Request({
    "data" : {
    "base64" : "akjshdkjashdkjashdkjahsdkjh",
    "vehicle_id" : "16"
    }
    }, headers={"Authorization": "Bearer {token_goes_here}"})
     * @response(200, body={
    "message": "success",
    "errors": {},
    "status_code": 200,
    "fail": 0,
    "return": {
    "inserted": true
    }
    })
     */
    public function add_vehicle_photo(Request $request){
        $currentUser = JWTAuth::parseToken()->authenticate();
        $credentials = $request->json()->get('data');


        //validate input
        $validator = Validator::make($credentials, [
            'base64' => 'required',
        ]);


        if(isset($credentials['vehicle_id']) && !empty($credentials['vehicle_id'])){
            $vehicle = Vehicle::where('user_id', $currentUser->id)->where('id', $credentials['vehicle_id'])->first();
        } elseif (isset($credentials['vehicle_number']) && !empty($credentials['vehicle_number'])){
            $vehicle = Vehicle::where('user_id', $currentUser->id)->where('vehicle_number', $credentials['vehicle_number'])->first();
        } else{
            return $this->response->errorBadRequest('Please specify a vehicle.');
        }

        if(is_null($vehicle)){
            return $this->response->errorBadRequest('Make sure you select a valid vehicle.');
        }

        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        $credentials['photo_id'] = $this->get_guid();
        $credentials['user_id'] = $currentUser->id;
        $credentials['vehicle_id'] = $vehicle->id;

        VehiclePhoto::where('user_id', $currentUser->id)->where('vehicle_id', $vehicle->id)->delete();
        $photo = VehiclePhoto::create($credentials);

        if($photo){
            return array("inserted" => true);
        } else{
            return $this->response->errorInternal('Something Went Wrong!');
        }
    }




    private function get_guid() {
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }

}