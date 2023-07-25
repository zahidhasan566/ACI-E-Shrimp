<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "Users";
    public $timestamps = false;
    public $primaryKey = 'Id';
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->Password;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function roles()
    {
        return $this->hasOne(Role::class,'RoleID','RoleID');
    }
    public function userSubmenu()
    {
        return $this->hasMany(SubMenuPermission::class,'UserId','Id');
    }
    public function getPondPreparation()
    {
        return $this->hasMany(Ponds::class,'UserId','Id')
            ->select(
                'UserId',
                    'PondId',
                    'Location',
                    'PondSizeInBigha',
                    'LandOwnershipBreakdown',
                    'Variety',
                    'NumberOfPond',
                    'Depth',
                    'PondPreparationMethod',
                    'PondImagePath',
                    DB::raw("FORMAT(CreatedAt,'dd-MM-yyyy') as CreatedAt"),
            );
    }

//    public function getPondPreparationCount()
//    {
//       return  $this->getPondPreparation();
//    }
//    public function getPondPreparationInfo(){
//        return  $this->getPondPreparation()->select('PondId','Location');
//    }

}
