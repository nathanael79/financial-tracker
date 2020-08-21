<?php


namespace App\Http\Controllers\Authentication;


use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function login(LoginRequest $request){
        $credentials = $request->only("email", "password");

        try{
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
            User::where('email', $request->email)->update(['login_at' => Carbon::now()]);
            return $this->responseJson($token, 'login successful', 200);

        }catch (JWTException $e){
            return $this->responseJson(null, 'could not create token', 500);
        }
    }
}
