<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Carbon\Carbon;

use App\Traits\UUID;

class Post extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable, UUID;

    protected $table = 'posts';
	public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid','post_title','post_content','user_id','post_status','isActive'
	];
	
	/**
     * Attributes to include in the Audit.
     *
     * @var array
     */
	protected $auditInclude = [
        'post_title','post_content','user_id','post_status','isActive'
    ];

    /**
     * Get the user that owns the post.
     */
    public function user(){
        return $this->belongsTo('App\Models\User\User','user_id');
    }

    /**
     * Get the comments for the post.
     */
    public function comments(){
        return $this->hasMany('App\Models\Comment\Comment','post_id');
    }

    /*Mutator and Setter Methods*/
    /**
     * Set the uuid
     *
     * @param string $UUID
     * @return void
     */
    public function setUUIDAttribute($UUID=""){$this->attributes['uuid'] = ($UUID !="") ? $UUID : null;}

    /**
	 * Set the title
	 *
	 * @param string $Title
	 * @return void
	 */
    public function setTitleAttribute($Title=""){$this->attributes['post_title'] = ($Title !="") ? $Title : null;}

    /**
	 * Set the content
	 *
	 * @param string $Content
	 * @return void
	 */
    public function setContentAttribute($Content=""){$this->attributes['post_content'] = ($Content !="") ? $Content : null;}

    /**
	 * Set the user id
	 *
	 * @param integer $ID
	 * @return void
	 */
    public function setUseIDAttribute($ID=0){$this->attributes['user_id'] = ($ID !="") ? $ID : null;}

    /**
	 * Get the created date
	 *
	 * @param string $value
	 * @return string
	 */
	public function getCreatedAtAttribute($value){
		if(!isset($value))return $value;
		if(isset(Auth::user()->timezone))return Carbon::parse($value)->setTimezone(Auth::user()->timezone)->format('Y-m-d H:i:s');
        return Carbon::parse($value)->setTimezone(env('APP_TIMEZONE'))->format('Y-m-d H:i:s');
	}
	
	/**
	 * Get the updated date
	 *
	 * @param string $value
	 * @return string
	 */
	public function getUpdatedAtAttribute($value){
		if(!isset($value))return $value;
		if(isset(Auth::user()->timezone))return Carbon::parse($value)->setTimezone(Auth::user()->timezone)->format('Y-m-d H:i:s');
        return Carbon::parse($value)->setTimezone(env('APP_TIMEZONE'))->format('Y-m-d H:i:s');
	}

    /**
	 * Get the deleted date
	 *
	 * @param string $value
	 * @return string
	 */
	public function getDeletedAtAttribute($value){
		if(!isset($value))return $value;
		if(isset(Auth::user()->timezone))return Carbon::parse($value)->setTimezone(Auth::user()->timezone)->format('Y-m-d H:i:s');
        return Carbon::parse($value)->setTimezone(env('APP_TIMEZONE'))->format('Y-m-d H:i:s');
	}

    /**
     * Get the id
     *
     * @param integer $value
     * @return integer
     */
    public function getPostIDAttribute($value){return (int)$value;}

    /**
     * Get the uuid
     *
     * @param  string  $value
     * @return string
     */
    public function getUUIDAttribute($value){return (string)$value;}

    /**
     * Get the title
     *
     * @param string $value
     * @return string
     */
    public function getTitleAttribute($value){return (string)$value;}

    /**
     * Get the content
     *
     * @param string $value
     * @return string
     */
    public function getContentAttribute($value){return (string)$value;}

     /**
     * Get the user id
     *
     * @param integer $value
     * @return integer
     */
    public function getUserIDAttribute($value){return (int)$value;}

    /**
     * Get the status
     *
     * @param string $value
     * @return string
     */
    public function getStatusAttribute($value){return (string)$value;}
}
