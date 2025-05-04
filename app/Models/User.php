<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmailContract
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, MustVerifyEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'whatsapp',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
        ];
    }

    /**
     * Get orders associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Override metode sendEmailVerificationNotification untuk mencegah Laravel mengirim email otomatis
     */
    public function sendEmailVerificationNotification(): void
    {
        // Tidak melakukan apa-apa untuk mencegah pengiriman email verifikasi bawaan Laravel
        \Illuminate\Support\Facades\Log::info('Mencegah pengiriman email verifikasi bawaan Laravel', [
            'user_id' => $this->id,
            'email' => $this->email
        ]);
    }

    /**
     * Override metode sendPasswordResetNotification untuk mencegah Laravel mengirim email reset password bawaan
     */
    public function sendPasswordResetNotification($token): void
    {
        // Tidak melakukan apa-apa untuk mencegah pengiriman email reset password bawaan Laravel
        \Illuminate\Support\Facades\Log::info('Mencegah pengiriman email reset password bawaan Laravel', [
            'user_id' => $this->id,
            'email' => $this->email
        ]);
    }
}
