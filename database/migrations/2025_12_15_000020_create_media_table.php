<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->unsignedBigInteger('size');
            $table->json('custom_properties');
            $table->json('generated_conversions')->nullable();
            $table->unsignedInteger('order_column')->nullable();
            $table->timestamps();

            $table->index(['model_type', 'model_id'], 'media_model_type_model_id_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
