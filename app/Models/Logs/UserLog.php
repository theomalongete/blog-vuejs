<?php

namespace App\Models\Logs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Traits\UUID;
use App\Models\User\User;

class UserLog extends Model
{
    use UUID;

    protected $table = "user_logs";
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'login_time', 'logout_time','ip_address','browser_name', 
        'version', 'platform','browser_device','country','status'
    ]; 

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'login_time' => 'datetime',
        'logout_time' => 'datetime',
    ];

    public static function index(){    
    }

    /*Mutator and Setter Methods*/
    /**
	 * Set the user id
	 *
	 * @param integer $ID
	 * @return void
	 */
    public function setUserIDAttribute($ID=0){$this->attributes['user_id'] = ($ID !="") ? $ID : null;}
    
    /**
	 * Set the login time
	 *
	 * @param string $Time
	 * @return void
	 */
    public function setLoginTimeAttribute($Time=""){$this->attributes['login_time'] = ($Time !="") ? $Time : null;}
    
    /**
	 * Set the logout time
	 *
	 * @param string $Time
	 * @return void
	 */
    public function setLogoutTimeAttribute($Time=""){$this->attributes['logout_time'] = ($Time !="") ? $Time : null;}
    
    /**
	 * Set the ip address
	 *
	 * @param string $IPAdress
	 * @return void
	 */
    public function setIPAdressAttribute($IPAdress=""){$this->attributes['ip_address'] = ($IPAdress !="") ? $IPAdress : null;}
    
    /**
	 * Set the broswer name
	 *
	 * @param string $Name
	 * @return void
	 */
    public function setBroswerNameAttribute($Name=""){$this->attributes['browser_name'] = ($Name !="") ? $Name : null;}
    
    /**
	 * Set the version
	 *
	 * @param string $Version
	 * @return void
	 */
    public function setVersionAttribute($Version=""){$this->attributes['version'] = ($Version !="") ? $Version : null;}
    
    /**
	 * Set the platform
	 *
	 * @param string $Platform
	 * @return void
	 */
    public function setPlatformAttribute($Platform=""){$this->attributes['platform'] = ($Platform !="") ? $Platform : null;}
    
    /**
	 * Set the broswer device
	 *
	 * @param string $BroswerDevice
	 * @return void
	 */
    public function setBroswerDeviceAttribute($BroswerDevice=0){$this->attributes['browser_device'] = ($BroswerDevice !="") ?$BroswerDevice : null;}
    
    /**
	 * Set the country
	 *
	 * @param string $Country
	 * @return void
	 */
    public function setCountryAttribute($Country=""){$this->attributes['country'] = ($Country !="") ? $Country : null;}
    
    /**
	 * Get the created date
	 *
	 * @param string $value
	 * @return string
	 */
	public function getCreatedAtAttribute($value){
		if(!isset($value))return $value;
		if(isset(Auth::user()->timezone)) return Carbon::createFromFormat('Y-m-d H:i:s', $value, Auth::user()->timezone)->format('Y-m-d H:i:s');
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
		if(isset(Auth::user()->timezone)) return Carbon::createFromFormat('Y-m-d H:i:s', $value, Auth::user()->timezone)->format('Y-m-d H:i:s');
        return Carbon::createFromFormat('Y-m-d H:i:s', $value, env('APP_TIMEZONE'))->format('Y-m-d H:i:s');
	}
    
    /**
     * Get the id
     *
     * @param string $value
     * @return string
     */
    public function getUserLogIDAttribute($value){return (int)$value;}

    /**
     * Get the account user id
     *
     * @param string $value
     * @return string
     */
    public function getUserIDAttribute($value){return (int)$value;}
    
    /**
     * Get the login time
     *
     * @param string $value
     * @return string
     */
    public function getLoginTimeAttribute($value){return (string)$value;}

    /**
     * Get the logout time
     *
     * @param string $value
     * @return string
     */
    public function getLogoutTimeAttribute($value){return (string)$value;}

    /**
     * Get the ip address
     *
     * @param string $value
     * @return string
     */
    public function getIPAdressAttribute($value){return (string)$value;}

    /**
     * Get the broswer name
     *
     * @param string $value
     * @return string
     */
    public function getBroswerNameAttribute($value){return (string)$value;}

    /**
     * Get the version
     *
     * @param string $value
     * @return string
     */
    public function getVersionAttribute($value){return (string)$value;}

    /**
     * Get the platform
     *
     * @param string $value
     * @return string
     */
    public function getPlatformAttribute($value){return (string)$value;}

    /**
     * Get the broswer device
     *
     * @param string $value
     * @return string
     */
    public function getBroswerDeviceAttribute($value){return (string)$value;}

    /**
     * Get the country
     *
     * @param string $value
     * @return string
     */
    public function getCountryAttribute($value){return (string)$value;}

    /**
     * Get the status
     *
     * @param string $value
     * @return string
     */
    public function getStatusAttribute($value){return (string)$value;}

    /**
     * Get the user/authentications logs
     *
     * @param integer $id
     * @return string
     */
    public static function authentications($id)
    {
        return static::all()->where('user_id',$id);
    }

    /**
     * Get the user last login at
     *
     * @param integer $id
     * @return string
     */
    public static function lastLoginAt($id)
    {
        $logs = static::authentications($id);
        return optional($logs->first())->login_time;
    }

    /**
     * Get the user last login ip address
     *
     * @param integer $id
     * @return string
     */
    public static function lastLoginIp($id)
    {
        $logs = static::authentications($id);
        return optional($logs->first())->ip_address;
    }

    /**
     * Get the user previous login at
     *
     * @param integer $id
     * @return string
     */
    public static function previousLoginAt($id)
    {
        $max = static::where('user_id', '=', $id)->orderby('id', 'desc')->first();
        $previous = static::where('id', '<', $max->id)->where('user_id', '=', $id)->orderby('id', 'desc')->first();
        return optional($previous)->login_time;
    }

    /**
	 * Get the user name
	 *
	 * @return string
	 */
	public function getUserName(){
        $user = User::find($this->user_id);
        if (!$user) {
            //Check if user is already deleted
            $user = User::withTrashed()->where('id',$this->user_id)->first();
        }
        $name = (isset($user->full_name))?$user->full_name:"(None)";        
		return (string)$name;
    }
}
