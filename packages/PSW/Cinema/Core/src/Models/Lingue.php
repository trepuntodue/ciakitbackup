<?php

namespace PSW\Cinema\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PSW\Cinema\Core\Contracts\Lingue as LingueContract;
use PSW\Cinema\Film\Models\LinguePage;

/**
 * Lingue class.
 * 
 * @package PSW\Cinema\Core\Models
 *
 * @property string $name
 * @property string $name_en
 * @property string $code
 * @property boolean $status


 * @property-read integer $id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
abstract class Lingue extends Model implements LingueContract
{
    /**
     * Table.
     *
     * @var string
     */

   protected $table = 'language';


    /**
     * Guarded.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'name_en',
        'code',
        'status',

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
