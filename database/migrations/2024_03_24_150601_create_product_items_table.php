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
        Schema::create('product_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code');
            $table->string('jan_code')->nullable();
            $table->string('name');
            $table->string('brand_name')->nullable();
            $table->text('description')->nullable();
            $table->string('item_status');
            $table->string('label_name')->nullable();
            $table->string('label_kana')->nullable();
            $table->string('label_sub_name')->nullable();
            $table->string('label_standard')->nullable();
            $table->text('label_description')->nullable();
            $table->string('food_content_name')->nullable();
            $table->string('food_content_ingredients')->nullable();
            $table->string('food_content_volume')->nullable();
            $table->integer('shelf_life')->nullable();
            $table->string('storage_method')->nullable();
            $table->unsignedBigInteger('manufacturer_id')->nullable();
            $table->foreign('manufacturer_id')->references('id')->on('companies');
            $table->string('allergen_display')->nullable();
            $table->decimal('nutritional_energy', 8, 1)->nullable();
            $table->decimal('nutritional_protein', 8, 1)->nullable();
            $table->decimal('nutritional_fat', 8, 1)->nullable();
            $table->decimal('nutritional_carbohydrate', 8, 1)->nullable();
            $table->decimal('nutritional_salt_equivalent', 8, 1)->nullable();
            $table->decimal('nutritional_ash', 8, 1)->nullable();
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
        Schema::dropIfExists('product_items');
    }
};
