<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    // Permitimos inserción masiva para estos campos
    protected $fillable = ['user_id', 'channel_id', 'product_id', 'quantity', 'sale_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}