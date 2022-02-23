<?php

namespace App\Models\User;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Carbon\Carbon;
use Log;

use App\Traits\UUID;
use App\Enums\UserStatus;
use App\Models\Post\Post;

class User extends Authenticatable implements Auditable, JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, \OwenIt\Auditing\Auditable, UUID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid','user_first_name','user_surname','email','email_verified_at','password','api_token','timezone','user_status','isActive'
    ];

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'user_first_name','user_surname','email','email_verified_at','password','timezone','user_status','isActive'
    ]; 

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password','api_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get all of the posts for the user.
     */
    public function posts(){
        return $this->hasMany('App\Models\Post\Post');
    }

    /**
     * GTet the e-mail address for the password reset
     *
     * @return string
     */
    public function getEmailForPasswordReset(){
        return $this->email;
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

    /*Mutator and Setter Methods*/
    /**
     * Set the name
     *
     * @param string $Name
     * @return void
     */
    public function setNameAttribute($Name=""){$this->attributes['user_first_name'] = ($Name !="") ? ucwords(strtolower($Name)) : null;}
    
    /**
     * Set the surname
     *
     * @param string $Name
     * @return void
     */
    public function setSurnameAttribute($Name=""){$this->attributes['user_surname'] = ($Name !="") ? ucwords(strtolower($Name)) : null;}

    /**
     * Set the e-mail address
     *
     * @param string $EmailAddress
     * @return void
     */
    public function setEmailAddressAttribute($EmailAddress=""){$this->attributes['email'] = ($EmailAddress !="") ? $EmailAddress : null;}

    /**
     * Set the e-mail verification date
     *
     * @param string $Date
     * @return void
     */
    public function setEmailAddressVerificationAttribute($Date=""){$this->attributes['email_verified_at'] = ($Date !="") ? $Date : null;}
    
    /**
     * Set the password
     *
     * @param string $Password
     * @return void
     */
    public function setPasswordAttribute($Password){
        
        if ( $Password !== null & $Password !== "" ){
            $this->attributes['password'] = ($Password !="") ? $Password : null;
        }
    }

    /**
     * Set the token
     *
     * @param string $Token
     * @return void
     */
    public function setAPITokenAttribute($Token=""){$this->attributes['api_token'] = ($Token !="") ? $Token:null;}

    /**
     * Set the timezone
     *
     * @param string $Timezone
     * @return void
     */
    public function setTimezoneAttribute($Timezone=""){$this->attributes['timezone'] = ($Timezone !="") ? $Timezone : null;}


    /**
     * Get the status
     *
     * @param string $Status
     * @return void
     */
    public function setStatusAttribute($Status=""){$this->attributes['user_status'] = ($Status !="") ? $Status : UserStatus::Active;}

    /**
	 * Get the created date
	 *
	 * @param string $value
	 * @return string
	 */
	public function getCreatedAtAttribute($value){
        if(!isset($value))return $value;
        $date = Carbon::parse($value)->format('Y-m-d H:i:s');
        if(isset(Auth::user()->timezone)) return Carbon::createFromFormat('Y-m-d H:i:s', $date, Auth::user()->timezone)->format('Y-m-d H:i:s');
        return Carbon::createFromFormat('Y-m-d H:i:s', $value, env('APP_TIMEZONE'))->format('Y-m-d H:i:s');
	}
	
	/**
	 * Get the updated date
	 *
	 * @param string $value
	 * @return string
	 */
	public function getUpdatedAtAttribute($value){
		if(!isset($value))return $value;
        $date = Carbon::parse($value)->format('Y-m-d H:i:s');
		if(isset(Auth::user()->timezone)) return Carbon::createFromFormat('Y-m-d H:i:s', $date, Auth::user()->timezone)->format('Y-m-d H:i:s');
        return Carbon::createFromFormat('Y-m-d H:i:s', $value, env('APP_TIMEZONE'))->format('Y-m-d H:i:s');
	}
	
	/**
	 * Get the deleted date
	 *
	 * @param string $value
	 * @return string
	 */
	public function getDeletedAtAttribute($value){
		if(!isset($value))return $value;
        $date = Carbon::parse($value)->format('Y-m-d H:i:s');
		if(isset(Auth::user()->timezone)) return Carbon::createFromFormat('Y-m-d H:i:s', $date, Auth::user()->timezone)->format('Y-m-d H:i:s');
        return Carbon::createFromFormat('Y-m-d H:i:s', $value, env('APP_TIMEZONE'))->format('Y-m-d H:i:s');
	}
    
    /**
     * Get the id
     *
     * @param integer $value
     * @return integer
     */
    public function getUserIDAttribute($value){return (int)$value;}

     /**
     * Get the name
     *
     * @param string $value
     * @return string
     */
    public function getNameAttribute($value){return(string) $value;}

    /**
     * Get the surname
     *
     * @param string $value
     * @return string
     */
    public function getSurnameAttribute($value){return(string) $value;}


    /**
     * Get the e-mail address
     *
     * @param string $value
     * @return string
     */
    public function getEmailAddressAttribute($value){return (string)$value;}

    /**
     * Get the e-mail address verifiaction date
     *
     * @param string $value
     * @return string
     */
    public function getEmailAddressVerificationAttribute($value){return (string)$value;}

    /**
     * Get the password
     *
     * @param string $value
     * @return string
     */
    public function getPasswordAttribute($value){return (string)$value;}

     /**
     * Get the api token
     *
     * @param string $value
     * @return string
     */
    public function getAPITokenAttribute($value){return (string)$value;} 

    /**
     * Get the timezone
     *
     * @param string $value
     * @return string
     */
    public function getTimezoneAttribute($value){return (string)$value;}

    /**
     * Get the status
     *
     * @param string $value
     * @return string
     */
    public function getStatusAttribute($value){return (string)$value;}

    /**
     * Get the full name
     *
     * @param string $value
     * @return string
     */
    public function getFullNameAttribute() {
        return ucfirst($this->user_first_name).' '.ucfirst($this->user_surname);
    }
}
