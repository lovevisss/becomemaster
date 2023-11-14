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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id'); //user who is paying
            $table->integer('qrcode_user_id')->nullable(); //user who is receiving
            $table->integer('qrcode_id'); //company who is receiving
            $table->string('payment_method')->nullable(); //paypal, stripe, paystack
            $table->longText('message')->nullable();
            $table->float('amount', 10, 4);
            $table->string('status')->default('pending'); //pending, success, failed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
