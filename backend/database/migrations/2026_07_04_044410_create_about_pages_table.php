<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('vision')->nullable();
            $table->jsonb('mission')->nullable();
            $table->jsonb('values')->nullable();
            $table->string('image')->nullable();
            $table->text('legal_info')->nullable();
            $table->jsonb('organization_structure')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_pages');
    }
};
