<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 精麦に使う原料との関連を記録するテーブル
        Schema::create('mill_purchase_material_polished', function (Blueprint $table) {
            $table->unsignedBigInteger('mill_purchase_material_id'); // 使用原料コード
            $table->foreign('mill_purchase_material_id', 'mpm_id')->references('id')->on('mill_purchase_materials');
            
            $table->unsignedBigInteger('mill_polished_material_id'); // 精麦済み原料コード
            $table->foreign('mill_polished_material_id', 'polished_id')->references('id')->on('mill_polished_materials');
            
            $table->decimal('input_weight', 8, 2)->nullable(); // この精麦工程で使った重量
            $table->decimal('input_cost', 8)->nullable(); // この工程で使った原価
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mill_purchase_material_polished');
    }
};