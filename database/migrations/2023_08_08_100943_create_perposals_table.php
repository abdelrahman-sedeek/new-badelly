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
        Schema::create('perposals', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('title');
            $table->boolean('isApproved')->nullable()->comment('0= not approved , 1=approved');
            $table->foreignId('product_id');
            $table->foreignId('product_Owner');
            $table->foreignId('user_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_Owner')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perposals');
    }
};
