<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'date', 'table_number', 'items', 'options', 'total_price', 'completion_status'];

    protected $casts = [
        'items' => 'json'
    ];

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_number', 'table_number');
    }
}
