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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('project_type_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('status_id')->nullable();
            $table->longText('description')->nullable();
             $table->integer('company_id')->unsigned()->nullable();
            $table->integer('days')->unsigned()->nullable();
            // $table->foreignId('company_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
