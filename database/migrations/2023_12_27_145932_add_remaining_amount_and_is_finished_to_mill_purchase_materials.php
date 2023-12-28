<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemainingAmountAndIsFinishedToMillPurchaseMaterials extends Migration
{
    public function up()
    {
        Schema::table('mill_purchase_materials', function (Blueprint $table) {
            $table->integer('remaining_amount')->after('total_amount')->default(0); // 残量を追加
            $table->boolean('is_finished')->after('remaining_amount')->default(false); // 使用終了フラグを追加
        });
    }

    public function down()
    {
        Schema::table('mill_purchase_materials', function (Blueprint $table) {
            $table->dropColumn('remaining_amount'); // 残量カラムを削除
            $table->dropColumn('is_finished'); // 使用終了フラグを削除
        });
    }
}