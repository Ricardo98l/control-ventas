<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Relación: Un canal tiene muchos productos con precios distintos
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('price')->withTimestamps();
    }

    // Relación: Un canal tiene muchas ventas
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}