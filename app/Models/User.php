<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Helpers\S3Helper;
use App\Models\Review;
use App\Models\Subscription;

/**
* @SWG\Definition(
* type="object",
* @SWG\Xml(name="User"),
* allOf={
*    @SWG\Schema(
*       required={"name", "email", "password"},
*       @SWG\Property(property="name", type="string", example="Owner"),
*       @SWG\Property(property="email", type="string", example="owner2@no-spam.ws"),
*       @SWG\Property(property="password", type="string", example="123456"),
*       @SWG\Property(property="avatar", type="integer", example="12"),
*    )
* }
* )
*/

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'created_at' => '',
        'updated_at' => ''
    ];
   
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($user){
            $us = self::find($user['id']);
            $avatar_id = $us->avatar;

            $us->avatar = null;
            $us->save();

            if (isset($user['avatar']) && $user['avatar']) {
                S3Helper::deleteFile($avatar_id);
            }  
        });
    }

    /**
     * Get the institutions for the user.
     */
    public function institutions()
    {
        return $this->hasMany('App\Models\Institution');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }    

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }    

    /**
    * @param string|array $roles
    */
    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) || 
                 abort(401, 'This action is unauthorized.');
        }
        
        return $this->hasRole($roles) || 
            abort(401, 'This action is unauthorized.');
    }

    /**
    * Check multiple roles
    * @param array $roles
    */
    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    /**
    * Check one role
    * @param string $role
    */
    public function hasRole($role)
    {
        if ($this->roles()->whereIn('name', explode('|', $role))->first()) {
            return true;
        }
        
        return false;
    }

    public function file()
    {
        return $this->belongsTo('App\Models\File', 'avatar');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }         
      
    /**
     * Get the subscriptions for the id.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
