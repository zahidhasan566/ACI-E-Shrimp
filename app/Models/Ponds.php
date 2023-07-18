<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ponds extends Model
{
    use HasFactory;
    protected $table = "Ponds";
    public $primaryKey = 'PondId';
    protected $guarded = [];
    public $timestamps = false;

    public function PondOperationInfo(){
        return $this->hasMany(PondDetails::class,'PondId','PondId')
            ->select([
                'PondId', 'SpfPl', 'Feed','BioSecurity','WaterPh','Salinity',
                'PLSource','AmountOfLoanDue','Probiotic','PLQuantity',
                DB::raw("FORMAT(PLReleaseDate,'dd-MM-yyyy') as PLReleaseDate"),
                'FeedSource',
                DB::raw("FORMAT(FeedReleaseDate,'dd-MM-yyyy') as FeedReleaseDate"),
                'DiseaseSymptoms',
                'ExpectedProductionQuantity',
                DB::raw("FORMAT(ExpectedProductionDate,'dd-MM-yyyy') as ExpectedProductionDate"),
                'Grade',
                'Transportation',
                DB::raw("FORMAT(CreatedAt,'dd-MM-yyyy') as CreatedAt"),
            ]);
    }
}
