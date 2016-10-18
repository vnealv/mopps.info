<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

use JWTAuth;
use Validator;
use App\History;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Dingo\Api\Exception\ValidationHttpException;

class HistoryController extends Controller
{
    use Helpers;

    /**
     * Get user history `PROTECTED`
     *
     * Get user history with `token`.
     *
     *
     * @Post("/api/history")
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
    "id": 23,
    "user_id": "1243",
    "type": "TopUp",
    "amount": "10",
    "date_unix": "1470816008",
    "vehicle_id": null,
    "vehicle_number": null,
    "parking_id": null,
    "parking_name": null,
    "surcharge": "0.00",
    "gst": "0.00",
    "tax": "0.00",
    "total": "10",
    "created_at": "2016-08-10 08:00:08",
    "updated_at": "2016-08-10 08:00:08",
    "deleted_at": null
    },
    {
    "id": 24,
    "user_id": "1243",
    "type": "Parking",
    "amount": "1.2",
    "date_unix": "1470817230",
    "vehicle_id": "16",
    "vehicle_number": "WJA2697",
    "parking_id": "1",
    "parking_name": "Puchong",
    "surcharge": "0.00",
    "gst": "0.00",
    "tax": "0.00",
    "total": "1.2",
    "created_at": "2016-08-10 08:20:30",
    "updated_at": "2016-08-10 08:20:30",
    "deleted_at": null
    }
    }
    })
     */
    public function history(Request $request)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();
//        $parkings = Parking::all();
        return History::where('user_id', $currentUser->id)->get();
    }
}
