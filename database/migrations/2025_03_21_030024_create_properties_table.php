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
        Schema::create('properties', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('type');
            $table->string('location');
            $table->text('map')->nullable();
            $table->double('minimum_price', 15, 2);
            $table->double('maximum_price', 15, 2);
            $table->double('minimum_area', 15, 2);
            $table->double('maximum_area', 15, 2);
            $table->string('status');
            $table->text('description');
            $table->boolean('isPublished');
            $table->boolean('isFeatured');
            $table->string('logo');
            $table->json('images');
            $table->json('amenities');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
