<?php

namespace Webkul\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PSW\Cinema\Core\Contracts\Generi as GeneriContract;
use PSW\Cinema\Film\Models\GeneriPage;

/**
 * Release class.
 * 
 * @package PSW\Cinema\Core\Models
 *
 * @property string $generi_name
 * @property string $generi_name_en
 * @property boolean $status


 * @property-read integer $id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
abstract class Generi extends Model implements GeneriContract
{
    /**
     * Table.
     *
     * @var string
     */

   protected $table = 'generi';


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
        'generi_name',
        'generi_name_en',
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
