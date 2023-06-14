<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PondDetails extends Model
{
    use HasFactory;
    protected $table = "PondDetails";
    public $primaryKey = 'PondDetailsId';
    protected $guarded = [];
    public $timestamps = false;
}
