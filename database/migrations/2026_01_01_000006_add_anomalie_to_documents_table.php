<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Signalement d'un ecart / anomalie comptable sur le document.
            $table->boolean('is_anomalie')->default(false)->after('description');
            // Montant de l'operation ou de l'ecart, en dinars tunisiens.
            $table->decimal('montant', 10, 3)->nullable()->after('is_anomalie');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['is_anomalie', 'montant']);
        });
    }
};
