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
            $table->decimal('remaining_polished_amount')->default(0)->after('polished_retention');
            $table->boolean('is_finished')->default(false)->after('remaining_polished_amount');
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
            $table->dropColumn('remaining_polished_amount');
            $table->dropColumn('is_finished');
        });
    }
};
