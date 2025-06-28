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
        Schema::create('Module_ImageAPI_Groups', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description")->nullable();
            $table->integer("sort")->default(0);
            $table->string("icon")->nullable();
            $table->string("color")->nullable();
            $table->boolean("is_active")->default(true);
            $table->float("max_size")->default(5.0); // Maximum size in MB
            $table->string("allowed_types")->default('jpg,jpeg,png,gif');

            $table->timestamps();
        
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Module_ImageAPI_Groups');
    }
};
