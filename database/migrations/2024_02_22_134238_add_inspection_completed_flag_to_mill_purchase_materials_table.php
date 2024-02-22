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
        Schema::table('mill_purchase_materials', function (Blueprint $table) {
            $table->boolean('inspection_completed')->after('cost')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mill_purchase_materials', function (Blueprint $table) {
            $table->dropColumn('inspection_completed');
        });
    }
};
