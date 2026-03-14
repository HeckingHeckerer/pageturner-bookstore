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
        'two_factor_enabled',
        'two_factor_code',
        'two_factor_expires_at',
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
'two_factor_enabled' => 'boolean',
'two_factor_expires_at' => 'datetime',
];

}

/**
 * Generate a two-factor authentication code
 */
public function generateTwoFactorCode()
{
    $this->two_factor_code = rand(1000, 9999);
    $this->two_factor_expires_at = now()->addMinutes(10);
    $this->save();
    
    return $this->two_factor_code;
}

/**
 * Verify a two-factor authentication code
 */
public function verifyTwoFactorCode($code)
{
    if ($this->two_factor_code === $code && $this->two_factor_expires_at > now()) {
        // Clear the code after successful verification
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
        return true;
    }
    
    return false;
}

/**
 * Disable two-factor authentication
 */
public function disableTwoFactor()
{
    $this->two_factor_enabled = false;
    $this->two_factor_code = null;
    $this->two_factor_expires_at = null;
    $this->save();
}

/**
 * Enable two-factor authentication
 */
public function enableTwoFactor()
{
    $this->two_factor_enabled = true;
    $this->save();
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