<?php

namespace PSW\Cinema\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PSW\Cinema\Core\Contracts\Release as ReleaseContract;
use Webkul\Customer\Models\Customer;

/**
 * Release class.
 *
 * @package PSW\Cinema\Core\Models
 *
 * @property string $original_title
 * @property integer $master_id
 * @property integer $customer_id
 * @property Customer $customer
 * @property string $other_title
 * @property string $release_year
 * @property string $country
 * @property string $release_distribution
 * @property string $releasetype
 * @property string $language
 * @property string $release_status
 * @property integer $default_release
 * @property boolean $release_vt18
 *
 * @property boolean $release_featured
 * @property string $url_key
 * @property-read integer $id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
abstract class Release extends Model implements ReleaseContract
{
    /**
     * Table.
     *
     * @var string
     */

   protected $table = 'releases';


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
        'customer_id',
        'master_id',
        'release_year',
        'country',
        'release_distribution',
        'language',
        'releasetype',
        'release_status',
        'default_release',
        'original_title',
        'other_title',
        'release_vt18',
        'release_featured',
        'url_key', // PWS#8-link-master
        'release_is_visible', // PWS#8-link-master
        'formato', // PWS#02-23
        'aspect_ratio', // PWS#02-23
        'camera_format', // PWS#02-23
        'region_code', // PWS#02-23
        'tipologia', // PWS#02-23
        'canali_audio', // PWS#02-23
        'subtitles', // PWS#02-23
        'durata', // PWS#02-23
        'contenuti_speciali', // PWS#02-23
        'numero_catalogo', // PWS#02-23
        'barcode', // PWS#02-23
        'crediti', // PWS#02-23
        'descrizione', // PWS#02-23
        'poster_tipo', // PWS#video-poster
        'poster_formato', // PWS#video-poster
        'poster_misure', // PWS#video-poster
        'illustratore', // PWS#video-poster
        'stampatore', // PWS#video-poster
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
