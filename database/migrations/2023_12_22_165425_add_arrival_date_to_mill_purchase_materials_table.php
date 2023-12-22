<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArrivalDateToMillPurchaseMaterialsTable extends Migration
{
    public function up()
    {
        Schema::table('mill_purchase_materials', function (Blueprint $table) {
            $table->date('arrival_date')->nullable()->after('materials_id'); // nullableを指定することで、入力は任意となります
        });
    }

    public function down()
    {
        Schema::table('mill_purchase_materials', function (Blueprint $table) {
            $table->dropColumn('arrival_date');  // ロールバック時にカラムを削除
        });
    }
}
