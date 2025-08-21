<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_id',
        'type',
        'data',
        'read_at'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function booted()
    {
        static::created(function ($user) {
            $filename = 'qrcodes/user_' . $user->id . '.png';
            $path = storage_path('app/public/' . $filename);

            \QrCode::format('png')->size(300)->generate(route('presence.scan', $user->id), $path);

            $user->update(['qr_code' => $filename]);
        });
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'responsable_id');
    }

    public function eglises()
    {
        return $this->hasMany(Eglise::class, 'pasteur_id');
    }

    public function tribus()
    {
        return $this->hasMany(Tribu::class, 'patriarche_id');
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    public function annonces()
    {
        return $this->hasMany(Tribu::class, 'patriarche_id');
    }
}
