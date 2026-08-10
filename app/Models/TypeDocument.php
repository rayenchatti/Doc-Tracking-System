<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeDocument extends Model
{
    protected $table = 'type_documents';

    protected $fillable = [
        'nom',
        'description',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'type_document_id');
    }
}
