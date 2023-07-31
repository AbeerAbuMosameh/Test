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
            $table->string('image')->nullable();
            $table->string('name');
            $table->string('model');
            $table->string('sku')->unique();
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
            $table->decimal('discount_price', 8, 2)->nullable();
            $table->decimal('cost_price', 8, 2)->nullable();
            $table->double('rate')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('keyword')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('product_tag')->nullable();
            $table->enum('status',['active','published','scheduled','inactive'])->default('active');
            $table->enum('in_stock',['yes','no'])->default('no');
            $table->enum('limited_inStock',['yes','no'])->default('no');
            $table->decimal('width', 8, 2);
            $table->decimal('height', 8, 2);
            $table->decimal('weight', 8, 2);
            $table->decimal('length', 8, 2);
            $table->text('description');
            $table->softDeletes();
            $table->timestamps();
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
