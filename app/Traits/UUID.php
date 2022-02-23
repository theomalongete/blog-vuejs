<?php
namespace App\Traits;

use Illuminate\Support\Str;

trait UUID
{
   /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
               //$model->{$model->getKeyName()}   = Str::orderedUuid()->toString();
                $model->uuid = Str::orderedUuid()->toString();
            }
        });
    }
   /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return true;
    }
   /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'int';
    }
}