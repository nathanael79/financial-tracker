<?php


namespace App\Http\Controllers\Authentication;


use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function registerUser(RegisterRequest $request){
        try{
            $data = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return $this->responseJson($data, 'success', 200);
        }catch (Exception $e){
            return $this->responseJson('',$e->getMessage(),500);
        }
    }

}
