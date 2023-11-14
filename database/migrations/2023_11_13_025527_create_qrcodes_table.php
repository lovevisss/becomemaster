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
        Schema::create('qrcodes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('qr_code');
            $table->string('website')->nullable();
            $table->string('product_name');
            $table->string('product_url')->nullable();
            $table->string('company_name');
            $table->string('callback_url')->nullable();
            $table->string('qrcode_path')->nullable(); // path to where QRcode is stored
            $table->float('amount', 8, 4);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qrcodes');
    }
};
