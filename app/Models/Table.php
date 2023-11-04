<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = ['table_number', 'status'];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($table) {
            $table->status = 1;
            $table->table_number = self::getNextTableNumber();
        });
    }

    public function getTableNumberAttribute($value)
    {
        return (int) $value;
    }

    private static function getNextTableNumber()
    {
        return Table::max('table_number') + 1;
    }

    public function orders()
    {
        return $this->hasOne(Order::class, 'table_number', 'table_number');
    }
}
