<?php

namespace App\Api\V1\Controllers;

use JWTAuth;
use Validator;
use Config;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Dingo\Api\Exception\ValidationHttpException;

/**
 * User authentication.
 *
 * @Resource("Authentication")
 */
class AuthController extends Controller
{
    use Helpers;

    /**
     * Login user
     *
     * Login a user with a `email` and `password`.
     *
     * @Post("/api/auth/login")
     * @Versions({"v1"})
     * @Request({"data" : {"email" : "foo@test.my","password" : "bar"}})
     * @response(200, body={"message": "success","errors": {},"status_code": 200,"fail": 0,"return": {"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEyNDMsImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvbW9wcHMuaW5mb1wvcHVibGljXC9hcGlcL2F1dGhcL2xvZ2luIiwiaWF0IjoxNDY5OTc2ODM1LCJleHAiOjE0Njk5ODA0MzUsIm5iZiI6MTQ2OTk3NjgzNSwianRpIjoiMGE4YWIyY2I2ZjYxMTEwNTk1NGZjNzM1NDk1ZGUwYmMifQ.yXfbYM2-gGQ-onuhsDvS82u_FJakKA0ehx6BbVNiOf4"}})
     */
    public function login(Request $request)
    {

//        $credentials = $request->only(['email', 'password']);
        $credentials = $request->json()->get('data');

        $validator = Validator::make($credentials, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->response->errorUnauthorized();
            }
        } catch (JWTException $e) {
            return $this->response->error('could_not_create_token', 500);
        }

        User::where('email', $credentials['email'])->update(['last_login' => time()]);
        return response()->json(compact('token'));
    }

    /**
     * Register user
     *
     * Register a new user with a `email` and `password`.
     *
     * @Post("/api/auth/signup")
     * @Versions({"v1"})
     * @Request({"data" : {"email" : "foo@test.my","password" : "bar"}})
     * @response(200, body={"message": "success","errors": {},"status_code": 200,"fail": 0,"return": {"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEyNDQsImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdFwvbW9wcHMuaW5mb1wvcHVibGljXC9hcGlcL2F1dGhcL3NpZ251cCIsImlhdCI6MTQ2OTk3NzM4MywiZXhwIjoxNDY5OTgwOTgzLCJuYmYiOjE0Njk5NzczODMsImp0aSI6ImNmMzIzMGJjMzhiMDc1MjE4NmYwOGFlN2UwZTdjYjQ3In0.t9xTP36okeD4dKg1TpOcDLF4kr8WrBYEL9nUq6jv0jA"}})
     */
    public function signup(Request $request)
    {

        $signupFields      = Config::get('boilerplate.signup_fields');
        $hasToReleaseToken = Config::get('boilerplate.signup_token_release');

//        $userData = $request->only($signupFields);
        $userData = $request->json()->get('data');

        $validator = Validator::make($userData, Config::get('boilerplate.signup_fields_rules'));

        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        User::unguard();
//        //set the random id length
//        $random_id_length = 6;
//
////generate a random id encrypt it and store it in $rnd_id
//        $rnd_id = crypt(uniqid(rand(), 1));
//
////to remove any slashes that might have come
//        $rnd_id = strip_tags(stripslashes($rnd_id));
//
////Removing any . or / and reversing the string
//        $rnd_id = str_replace(".", "", $rnd_id);
//        $rnd_id = strrev(str_replace("/", "", $rnd_id));
//
////finally I take the first 10 characters from the $rnd_id
//        $rnd_id = substr($rnd_id, 0, $random_id_length);
//        $userData['user_id'] = 'PPS01'.$rnd_id;
        $user = User::create($userData);
        $user->user_id = $this->generate_id($user->id);
        $user->save();

        User::reguard();

        if (!$user->id) {
            return $this->response->error('could_not_create_user', 500);
        }

        if ($hasToReleaseToken) {
            return $this->login($request);
        }

//        return $this->response->created();
        return array("created" => true);
    }


    //TODO: Fix recovery and reset
    /**
     * Password recovery
     *
     * Forgot your password. to recover account with `email` .
     *
     * @Post("/api/auth/recovery")
     * @Versions({"v1"})
     * @Request({"data" : {"email" : "foo@test.my"}})
     * @response(500, body="under maintenance")
     */
    public function recovery(Request $request)
    {
        $validator = Validator::make($request->json()->get('data'), [
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        $response = Password::sendResetLink($request->json()->get('data'), function (Message $message) {
            $message->subject(Config::get('boilerplate.recovery_email_subject'));
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return $this->response->noContent();
            case Password::INVALID_USER:
                return $this->response->errorNotFound();
        }
    }

    /**
     * Password reset
     *
     * Reset user password with `email`, `password`, `password_confirmation` and `token` .
     *
     * @Post("/api/auth/reset")
     * @Versions({"v1"})
     * @Request({"data" : {"email" : "foo@test.my", "password" : "bar", "password_confirmation" : "bar", "token" : "1AaksjhdkASDASDJ123kjn12kjh"}})
     * @response(500, body="under maintenance")
     */
    public function reset(Request $request)
    {
//        $credentials = $request->only(
//            'email', 'password', 'password_confirmation', 'token'
//        );

        $credentials = $request->json()->get('data');

        $validator = Validator::make($credentials, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            throw new ValidationHttpException($validator->errors()->all());
        }

        $response = Password::reset($credentials, function ($user, $password) {
            $user->password = $password;
            $user->save();
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                if (Config::get('boilerplate.reset_token_release')) {
                    return $this->login($request);
                }
                return $this->response->noContent();

            default:
                return $this->response->error('could_not_reset_password', 500);
        }
    }

    private function generate_id($id)
    {
        $seed = str_split('0123456789'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = 'PPS01'.sprintf("%04d", $id);
        foreach (array_rand($seed, 2) as $k) $rand .= $seed[$k];


        return $rand;
    }
}