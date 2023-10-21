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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description');
            $table->boolean("completed")->default(false);
            $table->foreignId('project_id')->constrained()->nullable();
            $table->foreignId('user_id')->constrained()->nullable();
            // $table->foreignId('company_id')->constrained()->nullable();
            $table->integer('days')->unsigned()->nullable();
            $table->integer('hours')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
