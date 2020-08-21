<?php


namespace App\Http\Controllers\Authentication;


use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(LoginRequest $request){
        try{
            $activeUser = User::where('email', $request->email)->first();
            if($activeUser == null){
                return $this->responseJson(null, 'email not found',403);
            }else{
                if(Hash::check($request->password, $activeUser->password)){
                    return $this->responseJson(null, 'success', 200);
                }else{
                    return $this->responseJson(null, 'password is wrong!', 403);
                }
            }
        }catch (Exception $e){
            return $this->responseJson(null, $e->getMessage(), 403);
        }
    }

}
