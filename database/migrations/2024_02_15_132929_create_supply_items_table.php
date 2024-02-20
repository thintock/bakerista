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
        Schema::create('supply_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code', 255)->nullable();
            $table->string('item_name', 255)->nullable();
            $table->string('standard', 255)->nullable();
            $table->string('brand_name', 255)->nullable();
            $table->string('category', 255)->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->mediumText('files')->nullable();
            $table->mediumText('print_images')->nullable();
            $table->string('item_status', 255)->nullable();
            $table->string('order_url')->nullable();
            $table->date('order_schedule')->nullable();
            $table->string('delivery_period', 255)->nullable();
            $table->integer('order_point')->nullable();
            $table->integer('order_lot')->nullable();
            $table->integer('constant_stock')->nullable();
            $table->integer('actual_stock')->nullable();
            $table->unsignedBigInteger('location_code')->nullable();
            $table->foreign('location_code')->references('id')->on('locations');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('supply_items');
    }
};
