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
        Schema::create('Module_ImageAPI', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('Name');
            $table->foreignId('group_id')->nullable()->constrained('Module_ImageAPI_Groups')->onDelete('set null');
            $table->text('path');
            $table->text('alt_text')->nullable();
            $table->text('tags')->nullable();
            $table->string('token', length: 48)->unique();
            $table->text('description')->nullable();
            $table->enum('status', ['public', 'private'])->default('private');

        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ImageAPI');
    }
};
