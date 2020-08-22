<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'v1'], function (){
    Route::namespace("Authentication")->group(function (){
        Route::post("/register","RegisterController@registerUser")->name("register");
        Route::post("/login", "LoginController@login")->name("login");
    });

    Route::group(['middleware' => ['jwt.verify']], function (){

        Route::namespace("User")->group(function (){
            Route::group(['prefix'=>'/user'], function (){
                Route::get('/', 'UserController@getUser')->name('get_user_profile');
            });
        });

        Route::namespace("Report")->group(function (){
            Route::group(['prefix' => '/report'], function (){
                Route::get('/{param}', 'ReportController@getAllFinanceTransaction')->name("get_summary_report");
                Route::get('/summary/{param}', 'ReportController@getSummary')->name("get_summary_report");
            });
        });


        Route::namespace("Finance")->group(function (){
            Route::group(['prefix' => '/account'], function (){
                Route::post("/create", "AccountController@createAccount")->name("create_account");
                Route::get("/all","AccountController@getAllAccount")->name("get_all_account");
                Route::get("/user-account","AccountController@getAccountInfo")->name("get_all_account_by_user");
                Route::get("/user-account/{id}","AccountController@getAccountInfoById")->name("get_all_account_by_id");
                Route::put("/update/{id}","AccountController@updateAccount")->name("update_account");
                Route::delete('/delete/{id}',"AccountController@delete")->name("delete_account");
            });

            Route::group(['prefix' => 'transaction'], function (){
                Route::post('/create','TransactionController@createTransaction')->name('create_transaction');
                Route::get('/all','TransactionController@getAllTransaction')->name('get_all_transaction');
                Route::get('/user/{id}','TransactionController@getAllTransactionByUser')->name('get_all_transaction_by_user');
                Route::get('/account/{id}','TransactionController@getAllTransactionByAccountId')->name('get_all_transaction_by_account');
                Route::get('/{id}','TransactionController@getTransactionById')->name('get_transaction_by_id');
                Route::delete('/{id}','TransactionController@deleteTransaction')->name('delete_transaction');
                Route::put("/{id}","TransactionController@updateTransaction")->name("update_transaction");
            });
        });
    });

});


