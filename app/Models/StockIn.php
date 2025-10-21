<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
