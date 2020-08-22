<?php


namespace App\Http\Controllers\Report;


use App\FinancialAccount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ReportController extends Controller
{
    public function getSummary(Request $request, $param){

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

}
