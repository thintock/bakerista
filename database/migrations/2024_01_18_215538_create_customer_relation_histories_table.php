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
        Schema::create('customer_relation_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_relation_id')->constrained('customer_relations');
            $table->foreignId('respondent_user_id')->constrained('users');
            $table->string('response_category')->nullable();
            $table->text('response_content')->nullable();
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
        Schema::dropIfExists('customer_relation_histories');
    }
};
