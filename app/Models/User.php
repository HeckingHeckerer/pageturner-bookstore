<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
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

public function addresses()
{
return $this->hasMany(Address::class);
}

public function cart()
{
return $this->hasMany(Cart::class);
}

public function cartItems()
{
return $this->cart();
}

public function reviews()
{
return $this->hasMany(Review::class);
}

/**
 * Get the notifications for the user.
 * Override the default Notifiable relationship to use our custom notifications table.
 */
public function notifications()
{
    return $this->hasMany(\App\Models\Notification::class, 'user_id');
}



// Helper method
public function isAdmin()
{
return $this->role === 'admin';
}
}