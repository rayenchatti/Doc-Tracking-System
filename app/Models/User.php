<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'password',
        'role',
        'statut',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getNomCompletAttribute(): string
    {
        return trim("{$this->prenom} {$this->nom}");
    }

    public function getNameAttribute(): string
    {
        return $this->nom_complet;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isActif(): bool
    {
        return $this->statut === 'actif';
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'utilisateur_id');
    }

    public function historiques(): HasMany
    {
        return $this->hasMany(Historique::class, 'utilisateur_id');
    }
}
