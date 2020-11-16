<?php

namespace Wink;

use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\Translatable\HasTranslations;
// use Wink\WinkRole;

class WinkAbility extends AbstractWinkModel 
{
  /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'abilities';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

     /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * An ability may have many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(WinkRole::class,'ability_role','ability_id','role_id')->withTimestamps();
    }

}