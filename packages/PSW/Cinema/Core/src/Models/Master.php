<?php

namespace PSW\Cinema\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PSW\Cinema\Core\Contracts\Master as MasterContract;
use PSW\Cinema\Film\Models\MasterPage;

/**
 * Release class.
 *
 * @package PSW\Cinema\Core\Models
 *
 * @property string $master_maintitle
 * @property int $master_id
 * @property integer $customer_id
 * @property string $master_othertitle
 * @property string $master_studios
 * @property string $country
 * @property string $master_director
 * @property string $master_actors
 * @property string $master_scriptwriters
 * @property string $master_musiccomposers
 * @property string $master_type
 * @property string $master_language
 * @property string $master_subgenres
 * @property string $master_genres
 * @property integer $master_year
 * @property boolean $master_vt18
 * @property boolean $master_is_visible
 * @property-read integer $id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
abstract class Master extends Model implements MasterContract
{
    /**
     * Table.
     *
     * @var string
     */

   protected $table = 'master';


    /**
     * Guarded.
     *
     * @var array
     */
    protected $guarded = [
        'master_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'master_id',
        'master_maintitle',
        'master_othertitle',
        'master_genres',
        'master_subgenres',
        'master_year',
        'country',
        'master_director',
        'master_actors',
        'master_scriptwriters',
        'master_musiccomposers',
        'master_studios',
        'master_language',
        'master_type',
        'customer_id',
        'master_is_visible',
        'master_vt18',
        'master_bn', // PWS#13-bn
        'casa_nome',
    ];

    /**
     * Castable.
     *
     * @var array
     */
    // protected $casts = [
    //     'additional'      => 'array',
    //     'default_release' => 'boolean',
    // ];

    /**
     * Get all the attributes for the attribute groups.
     *
     * @return string
     */
    // public function getNameAttribute(): string
    // {
    //     return $this->first_name . ' ' . $this->last_name;
    // }

    /**
     * Get the customer record associated with the address.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
