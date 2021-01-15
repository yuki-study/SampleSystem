<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->comment('ID');
            $table->string('year')->comment('対象年度');
            $table->tinyInteger('pattern')->unsigned()->default(1)->comment('1,2,3,4,5まで登録可能にする');
            $table->string('pattern_memo', 100)->nullable()->comment('パターンメモ');
            $table->integer('income')->unsigned()->comment('収入');
            $table->integer('expenses')->unsigned()->comment('経費');
            $table->tinyInteger('declaration_flg')->unsigned()->default(1)->comment('１：青色申告２：白色申告');
            $table->json('deductions')->nullable()->comment('所得控除内訳');
            $table->integer('insurance_fee_deduction')->unsigned()->comment('保険料_基礎控除');
            $table->double('insurance_fee_income', 5, 2)->comment('保険料_所得割率');
            $table->integer('insurance_fee_equal')->unsigned()->comment('保険料_均等割額');
            $table->double('municipal_tax_income', 5, 2)->comment('住民税_所得割率');
            $table->integer('municipal_tax_equal')->unsigned()->comment('住民税_均等割額');
            $table->integer('pension')->unsigned()->comment('月々の年金額');
            $table->timestamps();
            $table->softDeletes()->comment('排他削除');
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxes');
    }
}
