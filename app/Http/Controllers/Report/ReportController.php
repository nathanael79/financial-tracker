<?php


namespace App\Http\Controllers\Report;


use App\FinancialAccount;
use App\FinancialHistory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class ReportController extends Controller
{
    public function getAllFinanceTransaction(Request $request, $param){
        $validator = Validator::make($request->all(), [
            'year' => 'integer',
            'month' => 'integer|min:1|max:12',
            'date' => 'date_format:Y-m-d'
        ]);

        if ($validator->fails()) {
            return $this->responseJson(null, $validator->errors(), 422);
        }

        try{
            if($request->only('date')){
                $data = FinancialAccount::with(['getHistory' => function($query) use ($param, $request){
                    $query->where('type',$param)
                    ->whereDate('created_at','=',date('Y-m-d', strtotime($request->date)).' 00:00:00');
                }])
                    ->where('user_id',$this->getAuthenticatedUser()->id)
                    ->get();
            }elseif($request->only('year')){
                $data = FinancialAccount::with(['getHistory' => function($query) use ($param,$request){
                    $query->where('type',$param)
                        ->whereYear('created_at','=',$request->year);
                }])
                    ->where('user_id',$this->getAuthenticatedUser()->id)
                    ->get();
            }elseif($request->only('month')) {
                $data = FinancialAccount::with(['getHistory' => function($query) use ($param,$request){
                    $query->where('type',$param)
                        ->whereMonth('created_at','=',$request->month);
                }])
                    ->where('user_id',$this->getAuthenticatedUser()->id)
                    ->get();
            }else{
                $data = FinancialAccount::with(['getHistory' => function($query) use ($param){
                    $query->where('type',$param);
                }])
                    ->where('user_id',$this->getAuthenticatedUser()->id)
                    ->get();
            }

            if(empty($data)){
                return $this->responseJson(null, 'not found',404);
            }

            return $this->responseJson($data, 'success',200);

        }catch (Exception $e){
            return $this->responseJson(null, 'failed',500);
        }
    }

    public function getSummary($param){
        $userId = $this->getAuthenticatedUser()->id;
        $query = "SELECT YEAR(fah.created_at)  AS year, MONTH(fah.created_at)  AS month,  SUM(fah.amount)  AS total
                  FROM financial_account_history fah
                  WHERE fah.financial_account_id IN (
                    SELECT id FROM financial_account fa WHERE fa.user_id  =  '".$userId."'
                  )
                  AND fah.type = '".$param."'
                  GROUP BY YEAR(fah.created_at),MONTH(fah.created_at)";

        $data = DB::select($query);

        return $this->responseJson($data, 'success', 200);

    }

}
