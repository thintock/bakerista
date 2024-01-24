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
        Schema::create('customer_relation_selections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_relation_id')->constrained()->onDelete('cascade')->name('cust_rel_id');
            $table->foreignId('customer_relation_category_id')->constrained()->onDelete('cascade')->name('cust_rel_cat_id');
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
        Schema::dropIfExists('customer_relation_selections');
    }
};
