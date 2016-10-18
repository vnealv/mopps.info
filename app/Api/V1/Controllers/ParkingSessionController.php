<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

use JWTAuth;
use Validator;
use App\ParkingSession;
use App\Parking;
use App\UserAccount;
use App\Vehicle;
use App\History;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Dingo\Api\Exception\ValidationHttpException;


/**
 * Parking Session.
 *
 * @Resource("PSession")
 */
class ParkingSessionController extends Controller
{

    use Helpers;


    /**
     * Add a Parking Session`PROTECTED`
     *
     * Add a parking session with `token`.
     * `vehicle_id`, `start_unixtime`, `parking_duration`, `end_unixtime`, `parking_id`, `amount`, `latitude`, `longitude`, `confirmation`
     *
     * @Post("/api/psession/add")
     * @Versions({"v1"})
     * @Parameters({
     *     @parameter("vehicle_id", description="Vehicle id", required=true),
     *     @parameter("start_unixtime", description="Parking start time.", required=true),
     *     @parameter("parking_duration", description="Parking duration.", required=true),
     *     @parameter("end_unixtime", description="parking end time.", required=true),
     *     @parameter("parking_id", description="Which parking place is it", required=true),
     *     @parameter("amount", description="Amount of credit to be deducted.", required=true),
     *     @parameter("latitude", description="latitude", required=true),
     *     @parameter("longitude", description="longitude", required=true),
     *     @parameter("confirmation", description="this field is used in the case of duplication or editing an old session.", required=false, default="new OR update"),
     * })
     * @Request({"data":{
    "vehicle_id" : "16",
    "start_unixtime" : "1470448800",
    "parking_duration" : "1.75",
    "end_unixtime" : "1470455100",
    "parking_id" : "1",
    "amount" : "1.05",
    "latitude" : "WJA2697",
    "longitude" : "WJA2697",
    "confirmation" : "update"

    }}, headers={"Authorization": "Bearer {token_goes_here}"})
     * @response(200, body={
    "message": "success",
    "errors": {},
    "status_code": 200,
    "fail": 0,
    "return": {
    "created": true
    }
    })
     */
    public function add(Request $request)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
        $credentials = $request->json()->get('data');


        //validate input
        $validator = Validator::make($credentials, [
            'vehicle_id' => 'required',
            'start_unixtime' => 'required',
            'parking_duration' => 'required',
            'end_unixtime' => 'required',
            'parking_id' => 'required',
            'amount' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        $parking = Parking::where('id', $credentials['parking_id'])->first();
        //check the total amount to deduct.
        $parking_duration_c = abs($credentials['start_unixtime'] - $credentials['end_unixtime']) / 60 / 60;
        $parking_amount_c   = $parking_duration_c * (double)$parking->rate_per_hour;
        //Making sure all of the numbers have the same format.
        $parking_duration_c              = number_format((float)$parking_duration_c, 2, '.', '');
        $credentials['parking_duration'] = number_format((float)$credentials['parking_duration'], 2, '.', '');
        $parking_amount_c                = number_format((float)$parking_amount_c, 2, '.', '');
        $credentials['amount']           = number_format((float)$credentials['amount'], 2, '.', '');

        if (($parking_duration_c - $credentials['parking_duration']) != 0 || ($parking_amount_c - $credentials['amount']) != 0) {
            return $this->response->errorBadRequest('Parking Duration and amount to be deducted doesnt add up. Please make sure to pass the correct duration and amount.');
        }
        //Total amount and duration match

        //Check if vehicle exists.
        $vehicle = Vehicle::where('id', $credentials['vehicle_id'])->where('user_id', $currentUser->id)->first();

        if (is_null($vehicle)) {
            return $this->response->errorBadRequest('Vehicle does not exist!');
        }
        //Vehicle exists.

        //Check for duplicate entry.
        $duplicate = ParkingSession::where('vehicle_id', $credentials['vehicle_id'])->where('isActive', 1)->first();

        if ((!isset($credentials['confirmation']) || empty($credentials['confirmation'])) && !is_null($duplicate)) {
            //no confirmation and there is a duplicate
            return $this->response->error('There is an active parking session that exist for this vehicle.', 500);
        } elseif ((isset($credentials['confirmation']) && !empty($credentials['confirmation'])) && !is_null($duplicate)) {
            //there is confirmation and duplicate.
            switch ($credentials['confirmation']) {
                case "new":
                    //it will remove any active parking for this vehicle
                    ParkingSession::where('vehicle_id', $credentials['vehicle_id'])->where('isActive', 1)->update(['isActive' => 0]);
                    break;
                case "update":
                    $original_duration   = number_format((float)$duplicate->parking_duration, 2, '.', '');
                    $difference          = $parking_duration_c - $original_duration;
                    if ($difference <= 0) {
                        return $this->response->error('This is a duplicate request, nothing to update.', 500);
                    }
                    $parking_amount_c = $difference * (double)$parking->rate_per_hour;
                    ParkingSession::where('vehicle_id', $credentials['vehicle_id'])->where('isActive', 1)->update(['isActive' => 0]);
                    break;
                default:
                    return $this->response->error('Not valid confirmation value', 500);
            }

        }

        //Check if user has balance.
        $user_account = UserAccount::where('user_id', $currentUser->id)->first();
        $user_balance = number_format((float)$user_account->balance, 2, '.', '');
        if (($user_balance - $parking_amount_c) < 0) {
            return $this->response->errorBadRequest('Sorry, you don\'t have enough credit in your account.');
        }

        //User have enough balance.


        //TODO: Payment gateway go here.

        //Update user account balance.

        $user_account->balance = number_format((float)($user_balance - $parking_amount_c), 2, '.', '');

        $user_account->save();


        //Add the parking session to the database.
        $credentials['user_id']        = $currentUser->id;
        $credentials['isActive']       = 1;
        $credentials['vehicle_number'] = $vehicle->vehicle_number;

        $parking_session = ParkingSession::create($credentials);

        if (!$parking_session->id) {
            return $this->response->error('could_not_create_ParkingSession', 500);
        }

        History::create(['user_id' => $currentUser->id, 'type' => 'Parking', 'amount' => (float)$credentials['amount'], 'date_unix' => time(), 'total' => (float)$credentials['amount'], 'vehicle_id' => $credentials['vehicle_id'], 'vehicle_number' => $credentials['vehicle_number'], 'parking_id' => $parking->id, 'parking_name' => $parking->location_name]);
        return array("created" => true);
    }

    /**
     * Get parking spots list`PROTECTED`
     *
     * Get parking list with `token`.
     *
     *
     * @Post("/api/parking")
     * @Versions({"v1"})
     *
     * @Request({"data":{}}, headers={"Authorization": "Bearer {token_goes_here}"})
     * @response(200, body={
    "message": "success",
    "errors": {},
    "status_code": 200,
    "fail": 0,
    "return": {
    {
    "id": 1,
    "location_name": "Puchong",
    "street": "Jalan Puchong",
    "city": "Puchong",
    "state": "Selangor",
    "country": "Malaysia",
    "zip_code": "47120",
    "latitude": "3.0018988",
    "longitude": "101.5569429",
    "rate_per_hour": "0.60",
    "area_code": "PUC",
    "created_at": null,
    "updated_at": null,
    "deleted_at": null
    }
    }
    })
     */
    public function parkingList(Request $request)
    {
//        $parkings = Parking::all();
        return Parking::all();
    }

    /**
     * Get active parking session`PROTECTED`
     *
     * Get active parking session with `token`.
     *
     *
     * @Post("/api/psession/active")
     * @Versions({"v1"})
     *
     * @Request({"data":{}}, headers={"Authorization": "Bearer {token_goes_here}"})
     * @response(200, body={
    "message": "success",
    "errors": {},
    "status_code": 200,
    "fail": 0,
    "return": {
    {
    "id": 44,
    "user_id": "1243",
    "vehicle_id": "16",
    "isActive": "1",
    "start_unixtime": "1470808800",
    "parking_duration": "2.00",
    "end_unixtime": "1470816000",
    "parking_id": "1",
    "amount": "1.20",
    "latitude": "WJA2697",
    "longitude": "WJA2697",
    "vehicle_number": "WJA2697",
    "created_at": "2016-08-10 06:17:56",
    "updated_at": "2016-08-10 06:17:56",
    "deleted_at": null
    }
    }
    })
     */
    public function active_session(Request $request)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
        $credentials = $request->json()->get('data');

        return ParkingSession::where('user_id', $currentUser->id)->where('isActive', 1)->get();
    }

}
