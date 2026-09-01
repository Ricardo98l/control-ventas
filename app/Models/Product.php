<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'size', 'packaging_id'];

    // Relación: Un producto pertenece a muchos canales
    public function channels()
    {
        return $this->belongsToMany(Channel::class)->withPivot('price')->withTimestamps();
    }

    // Relación: Un producto tiene muchas ventas registradas
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function packaging() { return $this->belongsTo(Packaging::class); }
}