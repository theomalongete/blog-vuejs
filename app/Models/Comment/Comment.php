<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Contracts\Auditable;
use Carbon\Carbon;

use App\Traits\UUID;

class Comment extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable, UUID;

    protected $table = 'post_has_comments';
	public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid','comment_message','post_id','comment_status','isActive',
	];
	
	/**
     * Attributes to include in the Audit.
     *
     * @var array
     */
	protected $auditInclude = [
        'comment_message','post_id','comment_status','isActive'
    ];


    /**
     * Get the post that owns the comment.
     */
    public function post(){
        return $this->belongsTo('App\Models\Post\Post');
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
	 * Set the comment
	 *
	 * @param string $Comment
	 * @return void
	 */
    public function setCommentAttribute($Comment=""){$this->attributes['comment_message'] = ($Comment !="") ? $Comment : null;}

    /**
	 * Set the post id
	 *
	 * @param integer $ID
	 * @return void
	 */
    public function setPostIDAttribute($ID=0){$this->attributes['post_id'] = ($ID !="") ? $ID : null;}

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
    public function getCommentIDAttribute($value){return (int)$value;}

    /**
     * Get the uuid
     *
     * @param  string  $value
     * @return string
     */
    public function getUUIDAttribute($value){return (string)$value;}

    /**
     * Get the comment
     *
     * @param string $value
     * @return string
     */
    public function getCommentAttribute($value){return (string)$value;}

    /**
     * Get the id
     *
     * @param integer $value
     * @return integer
     */
    public function getPostIDAttribute($value){return (int)$value;}

    /**
     * Get the status
     *
     * @param string $value
     * @return string
     */
    public function getStatusAttribute($value){return (string)$value;}
}
