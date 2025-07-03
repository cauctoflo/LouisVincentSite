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
        Schema::table('sections', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('description'); // Font Awesome class (ex: fas fa-graduation-cap)
            $table->string('color')->default('blue')->after('icon'); // Couleur Tailwind (blue, green, purple, etc.)
            $table->string('image_url')->nullable()->after('color'); // URL de l'image
            $table->integer('display_order')->default(0)->after('image_url'); // Ordre d'affichage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn(['icon', 'color', 'image_url', 'display_order']);
        });
    }
};
