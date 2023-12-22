<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mill_purchase_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materials_id')->constrained()->onDelete('cascade');
            $table->string('year_of_production', 2);
            $table->string('flecon_number', 3);
            $table->integer('total_amount')->nullable();
            $table->string('lot_number', 11)->unique();
            $table->decimal('cost', 8, 2)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mill_purchase_materials');
    }
};
