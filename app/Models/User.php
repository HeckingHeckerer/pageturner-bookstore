<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
use HasFactory, Notifiable;
protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'default_shipping_address',
        'default_shipping_city',
        'default_shipping_state',
        'default_shipping_zip',
        'default_shipping_country',
    ];
protected $hidden = [
'password',
'remember_token',
];

protected function casts(): array
{
return [
'email_verified_at' => 'datetime',
'password' => 'hashed',
];

}
// Relationships
public function orders()
{
return $this->hasMany(Order::class);
}

public function cart()
{
return $this->hasMany(Cart::class);
}

public function reviews()
{
return $this->hasMany(Review::class);
}



// Helper method
public function isAdmin()
{
return $this->role === 'admin';
}
}