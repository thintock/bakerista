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
        Schema::create('customer_relations', function (Blueprint $table) {
            $table->id();
            $table->timestamp('received_at')->nullable();
            $table->foreignId('received_by_user_id')->constrained('users');
            $table->string('reception_channel')->nullable();
            $table->text('initial_content')->nullable();
            $table->string('product_name')->nullable();
            $table->string('customer_name')->nullable;
            $table->string('contact_number')->nullable();
            $table->string('link')->nullable();
            $table->text('images')->nullable(); 
            $table->boolean('needs_health_department_contact')->default(false);
            $table->text('health_department_contact_details')->nullable();
            $table->boolean('is_finished')->default(false); 
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
        Schema::dropIfExists('customer_relations');
    }
};
