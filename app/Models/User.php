<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users_212102';
    protected $primaryKey = 'id_212102'; // Tambahkan ini

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name_212102',
        'email_212102',
        'password_212102',
        'telephone_212102',
        'role_212102',
    ];

    protected $hidden = [
        'password_212102',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at_212102' => 'datetime',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'user_id_212102', 'id_212102');
    }
}
