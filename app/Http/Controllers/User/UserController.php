<?php


namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\User;
use Exception;
class UserController extends Controller
{
    public function getUser(){
        try{
            $data = User::find($this->getAuthenticatedUser()->id);
            if($data){
                return $this->responseJson($data, 'user found', 200);
            }else{
                return $this->responseJson(null, 'user not found', 404);
            }
        }catch (Exception $e){
            return $this->responseJson(null, 'failed', 500);
        }
    }

}
