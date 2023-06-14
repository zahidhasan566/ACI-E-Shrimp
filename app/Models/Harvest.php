<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harvest extends Model
{
    use HasFactory;
    protected $table = "Harvest";
    public $primaryKey = 'HarvestId';
    protected $guarded = [];
    public $timestamps = false;
}
