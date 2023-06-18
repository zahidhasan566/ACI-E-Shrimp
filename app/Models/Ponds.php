<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponds extends Model
{
    use HasFactory;
    protected $table = "Ponds";
    public $primaryKey = 'PondId';
    protected $guarded = [];
    public $timestamps = false;

    public function PondOperationInfo(){
        return $this->hasMany(PondDetails::class,'PondId','PondId');
    }
}
