<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->string('address_detail')->nullable();
            $table->string('phone');
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->text('Maps_url')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover')->nullable();
            $table->json('price_list')->nullable();
            $table->enum('verification_type', ['none', 'verified', 'official'])->default('none');
            $table->boolean('delivery_available')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('views_count')->default(0);
            $table->decimal('rating_avg', 2, 1)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('businesses');
    }
};