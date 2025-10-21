<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'name',
        'category',
        'stock',
        'unit',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }
}
