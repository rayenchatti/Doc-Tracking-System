<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 50)->unique();
            $table->string('nom', 200);
            $table->foreignId('type_document_id')->constrained('type_documents')->onDelete('cascade');
            $table->date('date_document');
            $table->text('description')->nullable();
            $table->string('fichier', 255)->nullable();
            $table->foreignId('statut_id')->constrained('statuts')->onDelete('cascade');
            $table->foreignId('utilisateur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
