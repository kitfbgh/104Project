<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('category');
            $table->float('origin_price');
            $table->float('price');
            $table->string('unit');
            $table->string('size')->nullable();
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->unsignedInteger('quantity');
            $table->string('imageUrl')->nullable();
            $table->string('image')->nullable()->default('images/noimage.jpeg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
