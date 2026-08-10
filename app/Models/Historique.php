<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Historique extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'document_id',
        'utilisateur_id',
        'ancien_statut_id',
        'nouveau_statut_id',
        'date_action',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function ancienStatut(): BelongsTo
    {
        return $this->belongsTo(Statut::class, 'ancien_statut_id');
    }

    public function nouveauStatut(): BelongsTo
    {
        return $this->belongsTo(Statut::class, 'nouveau_statut_id');
    }
}
