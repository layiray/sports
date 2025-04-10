<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('checkout_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('transaction_reference')->unique();
            $table->string('billing_name');
            $table->string('billing_address');
            $table->string('billing_city');
            $table->string('billing_zip');
            $table->string('billing_country');
            $table->string('shipping_method');
            $table->string('card_name');
            $table->string('card_number');
            $table->string('expiration_date');
            $table->string('cvv');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_details');
    }
};
