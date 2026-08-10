<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    protected $fillable = [
        'numero',
        'nom',
        'type_document_id',
        'date_document',
        'description',
        'fichier',
        'statut_id',
        'utilisateur_id',
        'service_id',
    ];

    public function typeDocument(): BelongsTo
    {
        return $this->belongsTo(TypeDocument::class, 'type_document_id');
    }

    public function statut(): BelongsTo
    {
        return $this->belongsTo(Statut::class, 'statut_id');
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function historiques(): HasMany
    {
        return $this->hasMany(Historique::class, 'document_id');
    }
}
