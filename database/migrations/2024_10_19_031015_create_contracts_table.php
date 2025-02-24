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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('year')->nullable();
            $table->unique(['year', 'name']);
            $table->string('path')->nullable();
            $table->double('amount')->nullable();
            $table->double('fulfillmentDeposit')->nullable();
            $table->double('paid_amount')->nullable();
            $table->unsignedInteger('project_id')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
