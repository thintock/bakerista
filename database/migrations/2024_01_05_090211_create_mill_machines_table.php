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
        Schema::create('mill_machines', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('machine_number')->unique();
            $table->string('machine_name')->nullable(); // 製粉機名
            $table->text('description')->nullable(); // 備考欄
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
        Schema::dropIfExists('mill_machines');
    }
};
