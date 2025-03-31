<?php

namespace PSW\Cinema\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PSW\Cinema\Core\Contracts\Sceneggiatori as SceneggiatoriContract;
use PSW\Cinema\Film\Models\SceneggiatoriPage;

/**
 * Release class.
 * 
 * @package PSW\Cinema\Core\Models
 *
 * @property string $scene_nome
 * @property string $scene_cognome
 * @property string $scene_alias
 * @property string $scene_nome_cognome
 * @property boolean $status
 * @property-read integer $id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
abstract class Sceneggiatori extends Model implements SceneggiatoriContract
{
    /**
     * Table.
     *
     * @var string
     */

   protected $table = 'sceneggiatori';


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
        'scene_nome',
        'scene_cognome',
        'scene_alias',
        'scene_nome_cognome',
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
