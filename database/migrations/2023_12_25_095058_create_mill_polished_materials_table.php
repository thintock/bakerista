<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mill_polished_materials', function (Blueprint $table) {
            $table->id();
            $table->date('polished_date'); //精麦日付
            $table->string('polished_lot_number', 15)->unique(); // 精麦済みロット番号
            $table->decimal('total_input_weight', 8, 2); // 総原料重量(kg)
            $table->decimal('total_output_weight', 8, 2); // 精麦後の総重量(kg)
            $table->decimal('total_input_cost', 8)->nullable(); // 原価
            $table->unsignedBigInteger('user_id'); // 作成ユーザー
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('mill_polished_materials');
    }
};
