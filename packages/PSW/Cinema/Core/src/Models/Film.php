<?php

namespace PSW\Cinema\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PSW\Cinema\Core\Contracts\Film as FilmContract;
use PSW\Cinema\Customer\Models\Customer;

/**
 * Film class.
 *
 * @package PSW\Cinema\Core\Models
 *
 * @property string $film_type
 * @property integer $customer_id
 * @property Customer $customer
 * @property string $first_name
 * @property string $last_name
 * @property string $gender
 * @property string $company_name
 * @property string $address1
 * @property string $address2
 * @property string $postcode
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string $email
 * @property string $phone
 * @property boolean $default_address
 * @property array $additional
 *
 * @property-read integer $id
 * @property-read string $name
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
abstract class Film extends Model implements FilmContract
{
    /**
     * Table.
     *
     * @var string
     */
    protected $table = 'films';

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
        'film_type',
        'customer_id',
        'cart_id',
        'order_id',
        'title_original',
        'other_titles',
        'genres',
        'subgenres',
        'year',
        'country',
        'director',
        'actors',
        'scriptwriters',
        'state',
        'music_composers',
        'studios',
        'language_original',
        'type',
        'release_year ',
        'distribution',
        'release_type',
        'release_language',
    ];

    /**
     * Castable.
     *
     * @var array
     */
    protected $casts = [
        'additional'      => 'array',
        'default_address' => 'boolean',
    ];

    /**
     * Get all the attributes for the attribute groups.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

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
