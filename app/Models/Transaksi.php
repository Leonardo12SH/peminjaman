<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi_212102';
    protected $primaryKey = 'id_212102';

    protected $fillable = [
        'user_id_212102',
        'item_id_212102',
        'no_transaksi_212102',
        'price',
        'total_price',
        'noted_212102',
        'start_time',
        'end_time',
        'status_212102',
    ];

    protected $dates = ['deleted_at', 'start_time', 'end_time'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id_212102', 'id_212102');
    }

    public function transaksi_details()
    {
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id_212102', 'id_212102');
    }

    public function item()
    {
        // Ganti App\Models\Item dengan model Item Anda yang sebenarnya
        // Ganti 'item_id_212102' jika foreign key berbeda
        // Ganti 'id_212102' jika primary key di model Item berbeda
        return $this->belongsTo(MenuItem::class, 'item_id_212102', 'id_212102');
    }
}
