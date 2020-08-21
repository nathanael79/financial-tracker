<?php


namespace App\Http\Controllers\Finance;


use App\FinancialAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;

class AccountController extends Controller
{
    public function createAccount(Request $request){
        try{
        $data = FinancialAccount::create(array_merge($request->all(),[
            'user_id' => $this->getAuthenticatedUser()->id
        ]));
        return $this->responseJson($data, 'success', 200);

        }catch (Exception $e){
            return $this->responseJson(null, 'failed',500);
        }
    }

    public function getAllAccount(){
        $data = FinancialAccount::all();
        return $this->responseJson($data, 'success', 200);
    }

    public function getAccountInfo(){
        try{
            $data = FinancialAccount::where('user_id', $this->getAuthenticatedUser()->id)->get();
            return $this->responseJson($data, 'success', 200);
        }catch (Exception $e){
            return $this->responseJson(null, 'failed',500);
        }
    }

    public function getAccountInfoById($id){
        try{
            $data = FinancialAccount::where('id', $id)->first();
            if(is_null($data)){
                return $this->responseJson(null, 'not found', 404);
            }
            return $this->responseJson($data, 'success', 200);
        }catch (Exception $e){
            return $this->responseJson(null, 'failed',500);
        }
    }

    public function updateAccount($id, Request $request){
        $validator = Validator::make($request->only(['name','limit']),[
            'limit' => 'required|integer|min:0',
            'name' => 'string'
        ]);

        if ($validator->fails()) {
            return $this->responseJson(null, $validator->errors(), 422);
        }

        $data = FinancialAccount::where('id',$id)->first();
        if(is_null($data)){
            return $this->responseJson(null, 'not found', 404);
        }else{
            $data->update($request->only(['name','limit']));

            return $this->responseJson( $data, 'updated', 200);
        }
    }

    public function delete($id){
        try{
            $data = FinancialAccount::where('id',$id)->first();
            if(is_null($data)){
                return $this->responseJson(null, 'not found', 404);
            }
            $data->delete();

            return $this->responseJson(null, 'deleted', 200);
        }catch (Exception $e){
            return $this->responseJson(null, 'failed',500);
        }
    }

}
