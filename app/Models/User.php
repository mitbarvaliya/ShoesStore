<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'registered_at',
        'password_updated_at',
        'otp_code',
        'otp_expires_at',
        'is_otp_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
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
            'registered_at' => 'datetime',
            'password_updated_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'is_otp_verified' => 'boolean',
        ];
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function generateOtp(): string
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->otp_code = $otp;
        $this->otp_expires_at = now()->addMinutes(10);
        $this->save();
        return $otp;
    }

    public function verifyOtp(string $otp): bool
    {
        if ($this->otp_code !== $otp) {
            return false;
        }

        if ($this->otp_expires_at->isPast()) {
            return false;
        }

        $this->is_otp_verified = true;
        $this->otp_code = null;
        $this->otp_expires_at = null;
        $this->save();
        return true;
    }

    public function clearOtp(): void
    {
        $this->otp_code = null;
        $this->otp_expires_at = null;
        $this->save();
    }
}
