<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'menu_items_212102';
    protected $primaryKey = 'id_212102';
    public $incrementing = true; // Tambahkan ini jika id_212102 auto-increment
    protected $keyType = 'int';  // Tambahkan ini jika id_212102 bertipe integer

    protected $fillable = [
        'menu_id_212102',
        'name_212102',
        'price_212102',
        'status_212102',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id_212102', 'id_212102');
    }

    public function transaksidetail()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
