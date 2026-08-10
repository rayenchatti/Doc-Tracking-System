<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Statut extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'nom',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'statut_id');
    }
}
