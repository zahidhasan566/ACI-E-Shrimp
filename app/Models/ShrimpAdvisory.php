<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShrimpAdvisory extends Model
{
    use HasFactory;
    protected $table = "ShrimpAdvisory";
    public $primaryKey = 'ShrimpAdvisoryId';
    protected $guarded = [];
    public $timestamps = false;
}
