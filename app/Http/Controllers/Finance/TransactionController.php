<?php


namespace App\Http\Controllers\Finance;


use App\FinancialAccount;
use App\FinancialHistory;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function createTransaction(Request $request){
        $validator = Validator::make($request->all(), [
            'financial_account_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'amount' => 'required|integer|min:0',
            'type' => 'required|in:debit,credit'
        ]);

        if ($validator->fails()) {
            return $this->responseJson(null, $validator->errors(), 422);
        }

        try{
            $financialAccount = FinancialAccount::where('id',$request->financial_account_id)->first();
            if($request->type == 'credit'){
                if(($financialAccount->limit > 0) && ($request->amount > $financialAccount->limit)){
                    return $this->responseJson(null, 'your amount is bigger than limit', 403);
                }elseif($request->amount > $financialAccount->balance){
                    return $this->responseJson(null, 'your amount is bigger than balance', 403);
                }else{
                    $data = FinancialHistory::create([
                        'financial_account_id' => $financialAccount->id,
                        'title' => $request->title,
                        'description' => $request->description,
                        'amount' => $request->amount,
                        'type' => 'credit'
                    ]);

                    $financialAccount->update([
                        'balance' => $financialAccount->balance - $request->amount
                    ]);
                }
            }else{
                $data = FinancialHistory::create([
                    'financial_account_id' => $financialAccount->id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'amount' => $request->amount,
                    'type' => 'debit'
                ]);

                $financialAccount->update([
                    'balance' => $financialAccount->balance + $request->amount
                ]);
            }

            return $this->responseJson($data, 'success', 200);
        }catch (Exception $e){
            return $this->responseJson(null, 'failed',500);
        }
    }

    public function updateTransaction(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer|min:0',
            'type' => 'required|in:debit,credit'
        ]);

        if ($validator->fails()) {
            return $this->responseJson(null, $validator->errors(), 422);
        }

        try{
            $financialHistory = FinancialHistory::where('id',$id)->first();
            $financialAccount = FinancialAccount::where('id', $financialHistory->financial_account_id)->first();
            if($request->type == 'credit'){
                if(($financialAccount->limit > 0) && ($request->amount > $financialAccount->limit)){
                    return $this->responseJson(null, 'your amount is bigger than limit', 403);
                }elseif($request->amount > $financialAccount->balance){
                    return $this->responseJson(null, 'your amount is bigger than balance', 403);
                }else{
                    $data = $financialHistory->update([
                        'title' => $request->title == null ? $financialHistory->title : $request->title,
                        'description' => $request->description == null ? $financialHistory->description : $request->description,
                        'amount' => $request->amount,
                        'type' => 'credit'
                    ]);

                    $financialAccount->update([
                        'balance' => $financialAccount->balance - $request->amount
                    ]);
                }
            }else{
                $data = $financialHistory->update([
                    'title' => $request->title == null ? $financialHistory->title : $request->title,
                    'description' => $request->description == null ? $financialHistory->description : $request->description,
                    'amount' => $request->amount,
                    'type' => 'debit'
                ]);

                $financialAccount->update([
                    'balance' => $financialAccount->balance + $request->amount
                ]);
            }

            return $this->responseJson($data, 'success', 200);
        }catch (Exception $e){
            return $this->responseJson(null, $e->getMessage(),500);
        }
    }

    public function getAllTransaction(){
        try{
            $data = FinancialHistory::all();
            if(empty($data)){
                return $this->responseJson(null, 'not found',404);
            }else{
                return $this->responseJson($data, 'success', 200);
            }
        }catch (Exception $e){
            return $this->responseJson(null, 'failed',500);
        }
    }

    public function getAllTransactionByUser($id){
        try{
            $data = FinancialAccount::with('getHistory')->where('user_id',$id)->get();
            if(empty($data)){
                return $this->responseJson(null, 'not found',404);
            }else{
                return $this->responseJson($data, 'success', 200);
            }
        }catch (Exception $e){
            return $this->responseJson(null, 'failed',500);
        }
    }

    public function getAllTransactionByAccountId($id){
        try{
            $data = FinancialHistory::where('financial_account_id',$id)->get();
            if(empty($data)){
                return $this->responseJson(null, 'not found',404);
            }else{
                return $this->responseJson($data, 'success', 200);
            }
        }catch (Exception $e){
            return $this->responseJson(null, 'failed',500);
        }
    }

    public function getTransactionById($id){
        try{
            $data = FinancialHistory::where('id',$id)->first();
            if(is_null($data)){
                return $this->responseJson(null, 'not found',404);
            }else{
                return $this->responseJson($data, 'success', 200);
            }
        }catch (Exception $e){
            return $this->responseJson(null, 'failed',500);
        }
    }

    public function deleteTransaction($id){
        try{
            $data = FinancialHistory::find($id);
            $data->delete();
            return $this->responseJson(null, 'deleted', 200);
        }catch (Exception $e){
            return $this->responseJson(null, 'failed',500);
        }
    }
}
