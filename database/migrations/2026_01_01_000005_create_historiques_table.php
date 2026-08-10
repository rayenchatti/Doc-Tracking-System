<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->foreignId('utilisateur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ancien_statut_id')->nullable()->constrained('statuts')->onDelete('cascade');
            $table->foreignId('nouveau_statut_id')->constrained('statuts')->onDelete('cascade');
            $table->timestamp('date_action')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historiques');
    }
};
