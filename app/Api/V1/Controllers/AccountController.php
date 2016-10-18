<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;

use JWTAuth;
use Validator;
use App\User;
use App\History;
use App\UserAccount;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Dingo\Api\Exception\ValidationHttpException;


/**
 * User account.
 *
 * @Resource("Account")
 */
class AccountController extends Controller
{
    use Helpers;
    //TODO: Payment gateway.


    /**
     * Topup Account Credit `PROTECTED`
     *
     * Add credit to user profile with `token`.
     * `amount`, `brain_token`
     *
     * @Post("/api/account/add")
     * @Versions({"v1"})
     * @Parameters({
     *     @parameter("amount", type="float", description="The amount to be added", required=true),
     *     @parameter("brain_token", description="TO BE DISCUSSED", required=false),
     * })
     * @Request({"data" : {"amount" : "22.50"}}, headers={"Authorization": "Bearer {token_goes_here}"})
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
    public function add(Request $request)
    {
        //TODO: Configure payment gateway.
        $currentUser = JWTAuth::parseToken()->authenticate();
        $credentials = $request->json()->get('data');

        $account = UserAccount::where('user_id', $currentUser->id)->first();

        //validate input
        $validator = Validator::make($credentials, [
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        if (is_null($account)) {
            $a = UserAccount::create(['balance' => (float)$credentials['amount'], 'user_id' => $currentUser->id]);
            if ($a->id) {
                $done = true;
            }
        } else {
//            $profile->account->balance = (string)((float)$profile->account->balance + (float)$credentials['amount']);
            $account->balance = (string)((float)$account->balance + (float)$credentials['amount']);
            $done                      = $account->save();
        }

        if ($done) {
            History::create(['user_id' => $currentUser->id, 'type' => 'TopUp', 'amount' => (float)$credentials['amount'], 'date_unix' => time(), 'total' => (float)$credentials['amount']]);
            return array("updated" => true);
        } else {
            return $this->response->errorInternal('Something Went Wrong!');
        }

    }


    /**
     * Get Account Credit `PROTECTED`
     *
     * Retrieve credit balance for a user with `token`.
     *
     * @Post("/api/account/add")
     * @Versions({"v1"})
     * @Request({"data":{}}, headers={"Authorization": "Bearer {token_goes_here}"})
     * @response(200, body={
    "message": "success",
    "errors": {},
    "status_code": 200,
    "fail": 0,
    "return": {
    "id": 9,
    "user_id": "1243",
    "balance": "22.5",
    "created_at": "2016-08-01 10:04:40",
    "updated_at": "2016-08-01 10:04:40"
    }
    })
     */
    public function show()
    {
        $currentUser = JWTAuth::parseToken()->authenticate();


        $account = UserAccount::where('user_id', $currentUser->id)->first();

        if (is_null($account)) {
            $account = UserAccount::create(['balance' => "0.00", 'user_id' => $currentUser->id]);

        }

        return $account;
    }


}
