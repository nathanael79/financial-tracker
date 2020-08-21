<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialHistory extends Model
{
    use SoftDeletes;
    protected $table = 'financial_account_history';
    protected $fillable =
        [
            'financial_account_id',
            'title',
            'description',
            'amount',
            'type'
        ];

    protected $dates = ['deleted_at'];

    public function getFinancialAccount(){
        return $this->hasOne(FinancialAccount::class,'id','financial_account_id');
    }
}
