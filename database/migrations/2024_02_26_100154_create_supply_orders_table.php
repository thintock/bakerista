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
        Schema::create('supply_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status')->nullable();
            $table->date('request_date')->nullable();
            $table->date('order_date')->nullable();
            $table->unsignedBigInteger('request_user')->nullable();
            $table->date('delivery_date')->nullable();
            $table->date('arrival_date')->nullable();
            $table->integer('order_quantity')->default(0);
            $table->integer('arrival_quantity')->default(0);
            $table->text('description')->nullable();
            $table->foreignId('item_id')->nullable()->constrained('supply_items');
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('location_id')->nullable()->constrained('loctions');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('supply_orders');
    }
};
