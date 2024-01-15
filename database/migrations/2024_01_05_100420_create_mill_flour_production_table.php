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
        Schema::create('mill_flour_productions', function (Blueprint $table) {
            $table->id();
            $table->string('production_lot_number', 24)->unique();
            $table->date('production_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->decimal('total_input_weight', 8, 2)->nullable();
            $table->decimal('total_input_cost', 10, 2)->nullable();
            $table->decimal('flour_weight', 8, 2)->nullable();
            $table->decimal('bran_weight', 8, 2)->nullable();
            $table->decimal('milling_retention', 5, 2)->nullable();
            $table->boolean('is_finished')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('mill_machine_id')->constrained('mill_machines');
            $table->foreignId('user_id')->constrained('users');
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
        Schema::dropIfExists('mill_flour_productions');
    }
};
