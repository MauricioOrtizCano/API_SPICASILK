<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'city',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    // Relación con las ordenes del usuario
    public function orders(): HasMany {
        return $this->hasMany(Order::class);
    }

    // Relación con el carrito del usuario
    public function cart(): HasOne {
        return $this->hasOne(Cart::class);
    }

    // Verificar si el usuario es administrador
    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    // Verificar si el usuario es cliente
    public function isCustomer(): bool {
        return $this->role === 'customer';
    }

    // Obtener lar ordenes que estan activas del usuario
    public function activeOrders(): HasMany {
        return $this->orders()->whereNotIn('status', ['completed', 'cancelled'])->orderBy('created_at', 'desc');
    }

    // Ontener el total gastado por el usuario
    public function totalSpent(): float {
        return $this->orders()
            ->where('status', 'completed')
            ->sum('total_amount');
    }

    // Scope para buscar usuarios activos
    public function scopeActive($query) {
        return $query->where('is_active', true);
    }

    // Scope para buscar usuarios inactivos
    public function scopeInactive($query) {
        return $query->where('is_active', false);
    }

    // Scope para filtrar por rol
    public function scopeRole($query, string $role) {
        return $query->where('role', $role);
    }

    // Arrancar el modelo
    protected static function boot() {
        parent::boot();

        // Evento para crear un carrito al crear un usuario
        static::created(function (User $user) {
            $user->cart()->create();
        });
    }


}
