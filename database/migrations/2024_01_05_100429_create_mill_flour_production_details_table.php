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
        Schema::create('mill_flour_production_details', function (Blueprint $table) {
            $table->id();
            $table->decimal('input_weight', 8, 2)->nullable();
            $table->decimal('input_cost', 10, 2)->nullable();
            $table->foreignId('mill_flour_production_id')->constrained('mill_flour_productions')->onDelete('cascade');
            $table->foreignId('mill_polished_material_id')->constrained('mill_polished_materials');
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
        Schema::dropIfExists('mill_flour_production_details');
    }
};
