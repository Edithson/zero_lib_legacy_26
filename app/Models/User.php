<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Type;
use App\Models\Order;
use App\Models\Download;
use Database\Factories\UserFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // #[Fillable(['name', 'email', 'password'])]
    #[Hidden(['password', 'remember_token'])]

    protected $fillable = [
        'name',
        'email',
        'type_id',
        'password',
        'provider',
        'provider_id',
        'provider_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Un utilisateur peut avoir plusieurs commandes.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // méthode pour les téléchargements
    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
