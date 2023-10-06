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
        Schema::create('accesses', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(0)->comment('0 = no active , 1 = active');
            $table->foreignId('perposal_id'); // auth->user
            $table->foreignId('product_id'); // product_owner_id
            $table->foreign('perposal_id')->references('id')->on('perposals');
            $table->foreign('product_id')->references('id')->on('products');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accesses');
    }
};
