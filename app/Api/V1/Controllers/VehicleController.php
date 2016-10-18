<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

use JWTAuth;
use Validator;
use App\User;
use App\Vehicle;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Dingo\Api\Exception\ValidationHttpException;

/**
 * User Vehicle.
 *
 * @Resource("Vehicle")
 */
class VehicleController extends Controller
{
    use Helpers;


    /**
     * Add a vehicle`PROTECTED`
     *
     * Add vehicle to user profile with `token`.
     * `brand`, `model`, `color`, `vehicle_number`
     *
     * @Post("/api/vehicle/add")
     * @Versions({"v1"})
     * @Parameters({
     *     @parameter("brand", description="Vehicle brand", required=true),
     *     @parameter("model", description="Vehicle model", required=true),
     *     @parameter("color", description="Vehicle color", required=true),
     *     @parameter("vehicle_number", description="Vehicle license number", required=true),
     * })
     * @Request({"data":{
    "brand" : "Proton",
    "model" : "Satria",
    "color" : "yellow",
    "vehicle_number" : "WJA2697"

    }}, headers={"Authorization": "Bearer {token_goes_here}"})
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
    public function add(Request $request)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
        $credentials = $request->json()->get('data');

        //validate input
        $validator = Validator::make($credentials, [
            'brand' => 'required',
            'model' => 'required',
            'color' => 'required',
            'vehicle_number' => 'required|unique:mopps_vehicle',
        ]);

        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        $vehicle = Vehicle::create($credentials);
        $user    = User::where('id', $currentUser->id)->first();
        if ($user->vehicles()->save($vehicle)) {
            return array("inserted" => true);
        } else {
            return $this->response->errorInternal('Something Went Wrong!');
        }
    }


    /**
     * list of vehicle(s)`PROTECTED`
     *
     * show vehicles listed under user profile with `token`.
     * `id` if no id present then it will show all the registered vehicles under this user.
     *
     * @Post("/api/vehicle")
     * @Versions({"v1"})
     * @Parameters({
     *     @parameter("id", description="Vehicle id", required=false),
     * })
     * @Request({"data":{
    "id" : "14",
    }}, headers={"Authorization": "Bearer {token_goes_here}"})
     * @response(200, body={
    "message": "success",
    "errors": {},
    "status_code": 200,
    "fail": 0,
    "return": {
    {
    "id": 14,
    "brand": "Proton",
    "model": "Satria",
    "color": "yellow",
    "vehicle_number": "WJA2697",
    "user_id": "1243",
    "photo_id": null,
    "created_at": "2016-08-02 05:28:49",
    "updated_at": "2016-08-02 05:40:07",
    "deleted_at": null
    }
    }
    })
     */
    public function show(Request $request)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
        $credentials = $request->json()->get('data');

        if (!empty($credentials) && isset($credentials['id'])) {
            $vehicles = Vehicle::where('id', $credentials['id'])->where('user_id', $currentUser->id)->with('vehicle_photo')->first();

        } else {
            $vehicles = Vehicle::where('user_id', $currentUser->id)->with('vehicle_photo')->get();
        }

        if (empty($vehicles)) {
            $vehicles = [];
        }

        return $vehicles;
    }


    /**
     * Update vehicle information`PROTECTED`
     *
     * update vehicle information with `token`.
     * `id`, `brand`, `model`, `color`, `vehicle_number`
     *
     * @Post("/api/vehicle/update")
     * @Versions({"v1"})
     * @Parameters({
     *     @parameter("id", description="Vehicle id", required=true),
     *     @parameter("brand", description="Vehicle brand", required=false),
     *     @parameter("model", description="Vehicle model", required=false),
     *     @parameter("color", description="Vehicle color", required=false),
     *     @parameter("vehicle_number", description="Vehicle license number", required=false),
     * })
     * @Request({"data":{
    "id" : "14",
    "brand" : "Proton",
    "model" : "Satria",
    "color" : "green",
    "vehicle_number" : "WJA2697"

    }}, headers={"Authorization": "Bearer {token_goes_here}"})
     * @response(200, body={
    "message": "success",
    "errors": {},
    "status_code": 200,
    "fail": 0,
    "return": {
    "updated": true
    }
    })
     */
    public function update(Request $request)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
        $credentials = $request->json()->get('data');

        //validate input
        $validator = Validator::make($credentials, [
            'id' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'color' => 'required',
            'vehicle_number' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        if (Vehicle::where('id', $credentials['id'])->where('user_id', $currentUser->id)->update($credentials)){
            return array("updated" => true);
        } else {
            return $this->response->errorInternal('Something Went Wrong!');
        }
    }

    /**
     * delete vehicle `PROTECTED`
     *
     * delete vehicle  with `token`.
     * `id`
     *
     * @Post("/api/vehicle/delete")
     * @Versions({"v1"})
     * @Parameters({
     *     @parameter("id", description="Vehicle id", required=true),
     * })
     * @Request({"data":{
    "id" : "14"
    }}, headers={"Authorization": "Bearer {token_goes_here}"})
     * @response(200, body={
    "message": "success",
    "errors": {},
    "status_code": 200,
    "fail": 0,
    "return": {
    "deleted": true
    }
    })
     */
    public function delete(Request $request)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
        $credentials = $request->json()->get('data');

        //validate input
        $validator = Validator::make($credentials, [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        if (Vehicle::destroy($credentials['id'])){
            return array("deleted" => true);
        } else {
            return $this->response->errorInternal('Something Went Wrong!');
        }
    }
}
