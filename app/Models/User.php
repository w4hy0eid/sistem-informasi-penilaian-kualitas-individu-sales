<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nik',
        'email',
        'password',
        'level',
        'tr',
        'segmen',
        'updated_at',
        'role',
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
    ];

    public function realisasiScalings()
    {
        return $this->hasMany(RealisasiScaling::class);
    }


    public function realisasiNgtmas()
    {
        return $this->hasMany(RealisasiNgtma::class);
    }

    public function realisasiSustains()
    {
        return $this->hasMany(RealisasiSustain::class);
    }

    public function targetNgtmas()
    {
        return $this->hasMany(TargetNgtma::class);
    }

    public function targetScalings()
    {
        return $this->hasMany(TargetScaling::class);
    }

    public function targetSustains()
    {
        return $this->hasMany(TargetSustain::class);
    }

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }
}
