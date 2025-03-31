<?php

namespace PSW\Cinema\Customer\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use PSW\Cinema\Core\Models\Release;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PSW\Cinema\Customer\Database\Factories\CustomerReleaseFactory;
use PSW\Cinema\Customer\Contracts\CustomerRelease as CustomerReleaseContract;

class CustomerRelease extends Release implements CustomerReleaseContract
{
    use HasFactory;

   // public const ADDRESS_TYPE = 'customer';

    /**
     * @var array default values
     */
    // protected $attributes = [
    //     'address_type' => self::ADDRESS_TYPE,
    // ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    // protected static function boot(): void
    // {
    //     static::addGlobalScope('address_type', static function (Builder $builder) {
    //         $builder->where('address_type', self::ADDRESS_TYPE);
    //     });

    //     parent::boot();
    // }

    /**
     * Create a new factory instance for the model
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return CustomerReleaseFactory::new();
    }
}
