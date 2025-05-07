<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'menus_212102';
    protected $primaryKey = 'id_212102'; // <-- ini penting!

    protected $guarded = [];

    public function menuitems()
    {
        return $this->hasMany(MenuItem::class);
    }
}

 