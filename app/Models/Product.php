<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount'
    ];


    public function transactions()
    {
        return $this->belongsToMany(
            \App\Models\Transaction::class,
            'transaction_products'
        )->withPivot('quantity')->withTimestamps();
    }
}