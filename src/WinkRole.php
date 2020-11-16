<?php

namespace Wink;

use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\Translatable\HasTranslations;
// use Wink\WinkAbility;

class WinkRole extends AbstractWinkModel 
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
    protected $table = 'roles';

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
     * A role may have many abilities.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function abilities()
    {
        return $this->belongsToMany(WinkAbility::class,'ability_role','role_id','ability_id')->withTimestamps();
    }

    /**
     * Grant the given ability to the role.
     *
     * @param  mixed  $ability
     */
    public function allowTo($ability)
    {
        if (is_string($ability)) {
            $ability = WinkAbility::whereName($ability)->firstOrFail();
        }

        $this->abilities()->sync($ability, false);
    }

}