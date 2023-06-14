<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerProducts extends Model
{
    use HasFactory;
    protected $table = "BuyerProducts";
    public $primaryKey = 'BuyerProductId';
    protected $guarded = [];
    public $timestamps = false;
}
