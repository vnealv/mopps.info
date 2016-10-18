<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

use JWTAuth;
use App\User;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;

/**
 * User profile.
 *
 * @Resource("Profile")
 */
class ProfileController extends Controller
{
    use Helpers;


    /**
     * User Profile `PROTECTED`
     *
     * Get user profile with `token`.
     *
     * @Post("/api/profile")
     * @Versions({"v1"})
     * @Request({"data" : {}}, headers={"Authorization": "Bearer {token_goes_here}"})
     * @response(200, body={
    "message": "success",
    "errors": {},
    "status_code": 200,
    "fail": 0,
    "return": {
    {
    "id": 1237,
    "user_id": "PPS01591999",
    "public_user_id": "SP047812",
    "name": "munfai L",
    "email": "a@a.com",
    "usergroup": "user",
    "photo_id": "EA8EC494-4605-4F9B-B287-C8BDA04C2D84",
    "status": "active",
    "phone_number": "0162782984",
    "joined_unix": null,
    "last_login": "1467736421",
    "isPhoneVerified": "0",
    "isEmailVerified": "0",
    "isResetPassword": "0",
    "created_at": null,
    "updated_at": null,
    "vehicles": {
    {
    "id": 2,
    "brand": "Perodua",
    "model": "Myvi",
    "color": "Yellow",
    "vehicle_number": "W1234A",
    "user_id": "1237",
    "photo_id": "010CFAA1-C87C-4698-AE87-CF7D73FAFF9B"
    },
    {
    "id": 3,
    "brand": "Proton",
    "model": "Wira",
    "color": "Maroon",
    "vehicle_number": "BMM1234",
    "user_id": "1237",
    "photo_id": "12A7EBFA-6AF7-4AFD-9DD2-91EA1923B316"
    },
    {
    "id": 4,
    "brand": "Toyota",
    "model": "Vios",
    "color": "White",
    "vehicle_number": "AMD9989",
    "user_id": "1237",
    "photo_id": "59D5E33C-2327-49B6-8B5F-266AC7574AD9"
    }
    }
    }
    }
    })
     */
    public function show(Request $request)
    {
        $currentUser = JWTAuth::parseToken()->authenticate();

        $data = collect(User::where('id', $currentUser->id)->with('vehicles')->with('photo')->get());

        return $data;
    }


    /**
     * Update User Profile `PROTECTED`
     *
     * Update user profile with `token`.
     * `name`, `phone_number`, `password`, `photo`
     *
     * @Post("/api/profile/update")
     * @Versions({"v1"})
     * @Parameters({
     *     @parameter("name", description="Name field in the user profile", required=false),
     *     @parameter("phone_number", description="User phone number", required=false),
     *     @parameter("password", description="User new password", required=false),
     *     @parameter("photo", description="TO BE DISCUSSED", required=false),
     * })
     * @Request({"data" : {"param_field_name" : "param_value"}}, headers={"Authorization": "Bearer {token_goes_here}"})
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
        $allowed_fields = ['name', 'phone_number', 'password'];

        $credentials = $request->json()->get('data');

        $profile = User::where('id', $currentUser->id)->first();

        $updated = false;
        foreach ($credentials AS $k => $v) {
            if (in_array($k, $allowed_fields)) {
                $profile->$k = $v;
                $updated     = true;
            }
        }
        if ($updated) {
            if ($profile->save()) {
                return array("updated" => true);
            } else {
                return $this->response->errorInternal('Something Went Wrong!');
            }
        }else{
            return $this->response->errorBadRequest('No Fields to be updated. Please make sure the name of the fields are correct.');
        }

    }


}
