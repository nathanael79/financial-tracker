<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialAccount extends Model
{
    use SoftDeletes;
    protected $table = 'financial_account';
    protected $fillable =
        [
            'name',
            'user_id',
            'limit',
            'balance'
        ];

    protected $dates = ['deleted_at'];

    public function getHistory(){
        return $this->hasMany(FinancialHistory::class,'financial_account_id','id');
    }
}
