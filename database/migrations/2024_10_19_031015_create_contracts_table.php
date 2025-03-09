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
            $table->integer('year');
            $table->unique(['year', 'name']);  //合同年份和合同名称唯一 , 存在维保合同名称相同的情况
            $table->string('path')->nullable();
            $table->date('signing_date'); //签订日期
            $table->double('amount'); //合同金额
            $table->unsignedInteger('company_id'); //公司
            $table->double('fulfillmentDeposit')->nullable(); //质保金
            $table->double('paid_amount')->nullable();
            $table->unsignedInteger('status_id')->default(0); //合同状态 2
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
