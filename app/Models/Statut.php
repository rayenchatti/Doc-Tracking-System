<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Statut extends Model
{
    public $timestamps = false;

    /**
     * Cycle de vie fixe d'un document, dans l'ordre.
     * En attente -> En cours -> Traité -> Archivé
     */
    public const ORDRE = ['En attente', 'En cours', 'Traité', 'Archivé'];

    protected $fillable = [
        'nom',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'statut_id');
    }
}
