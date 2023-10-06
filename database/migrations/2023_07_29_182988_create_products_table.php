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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("description");
            $table->string("image1");
            $table->string("image2");
            $table->string("image3")->nullable();
            $table->string("image4")->nullable();
            $table->string("video")->nullable();
            $table->boolean("isActive")->default(false);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            
            $table->foreignId('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            
            $table->foreignId('subcategory_id')->nullable();
            $table->foreign('subcategory_id')->references('id')->on('subcategories');
            
            $table->foreignId('subsubcategory_id')->nullable();
            $table->foreign('subsubcategory_id')->references('id')->on('subsubcategories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
