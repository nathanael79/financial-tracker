<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialAccountHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_account_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("financial_account_id");
            $table->foreign("financial_account_id")->references("id")->on("financial_account");
            $table->string("title");
            $table->text("description")->nullable();
            $table->bigInteger("amount");
            $table->enum("type",["debit","credit"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financial_account_history');
    }
}
