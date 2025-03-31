<?php

namespace PSW\Cinema\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PSW\Cinema\Core\Contracts\Actory as ActoryContract;
use PSW\Cinema\Film\Models\ActoryPage;

/**
 * Release class.
 * 
 * @package PSW\Cinema\Core\Models
 *
 * @property string $attori_nome
 * @property string $attori_cognome
 * @property string $attori_alias
 * @property string $attori_nome_cognome
 * @property boolean $status
 * @property-read integer $id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
abstract class Actory extends Model implements ActoryContract
{
    /**
     * Table.
     *
     * @var string
     */

   protected $table = 'attori';


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
        'attori_nome',
        'attori_cognome',
        'attori_alias',
        'attori_nome_cognome',
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
