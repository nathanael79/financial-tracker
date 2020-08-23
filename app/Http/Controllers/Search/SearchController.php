<?php


namespace App\Http\Controllers\Search;


use App\FinancialAccount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

class SearchController extends Controller
{
    public function search(Request $request, $limit = 10, $page = 1, $offset = 0){

        $q = $request->input('keyword');

        if(is_null($q)){
            return $this->responseJson(null, 'please use at least 1 keyword', 500);
        }

        try {
            $data = FinancialAccount::join('financial_account_history', 'financial_account.id', '=', 'financial_account_history.financial_account_id')
                ->select(
                    'financial_account.name',
                    'financial_account.type AS account_type',
                    'financial_account.limit',
                    'financial_account.balance',
                    'financial_account_history.title',
                    'financial_account_history.description',
                    'financial_account_history.amount',
                    'financial_account_history.type AS finance_type'
                )->where('name', 'like', '%' . $q . '%')
                ->orWhere('financial_account.type', 'like', '%' . $q . '%')
                ->orWhere('limit', 'like', '%' . $q . '%')
                ->orWhere('balance', 'like', '%' . $q . '%')
                ->orWhere('title', 'like', '%' . $q . '%')
                ->orWhere('description', 'like', '%' . $q . '%')
                ->orWhere('amount', 'like', '%' . $q . '%')
                ->orWhere('financial_account_history.type', 'like', '%' . $q . '%')
                ->offset($offset)
                ->limit($limit)
                ->get();

            if (is_null($data)) {
                return $this->responseJson(null, 'not found', 404);
            }

            return $this->responseJson($data, 'success', 200);
        }catch (Exception $e){
            return $this->responseJson(null, $e->getMessage(), 500);
        }
    }

}
