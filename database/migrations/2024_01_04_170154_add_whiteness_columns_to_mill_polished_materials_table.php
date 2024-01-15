<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mill_polished_materials', function (Blueprint $table) {
            
            // 精麦歩留率
            $table->decimal('polished_retention', 4,1)->after('total_input_cost')->nullable();
            
            // whiteness columns 白度記録用
            $table->decimal('mill_whiteness_1', 3, 1)->after('polished_retention')->nullable();
            $table->decimal('mill_whiteness_2', 3, 1)->after('mill_whiteness_1')->nullable();
            $table->decimal('mill_whiteness_3', 3, 1)->after('mill_whiteness_2')->nullable();
            $table->decimal('mill_whiteness_4', 3, 1)->after('mill_whiteness_3')->nullable();
            $table->decimal('mill_whiteness_5', 3, 1)->after('mill_whiteness_4')->nullable();
            $table->decimal('mill_whiteness_6', 3, 1)->after('mill_whiteness_5')->nullable();
            $table->decimal('mill_whiteness_7', 3, 1)->after('mill_whiteness_6')->nullable();
            $table->decimal('mill_whiteness_8', 3, 1)->after('mill_whiteness_7')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mill_polished_materials', function (Blueprint $table) {
            // drop if exists
            $table->dropColumn([
                'polished_retention',
                'mill_whiteness_1',
                'mill_whiteness_2',
                'mill_whiteness_3',
                'mill_whiteness_4',
                'mill_whiteness_5',
                'mill_whiteness_6',
                'mill_whiteness_7',
                'mill_whiteness_8'
            ]);
        });
    }
};
