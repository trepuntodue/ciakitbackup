<?php

namespace PSW\Cinema\Film\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use PSW\Cinema\Film\Contracts\MasterPage as MasterContract;
use PSW\Cinema\Film\Database\Factories\MasterFactory;
use PSW\Cinema\Film\Type\AbstractType;

class MasterPage extends Model implements MasterContract
{
    use HasFactory;
/**
     * The type of product.
     *
     * @var \PSW\Cinema\Film\Type\AbstractType
     */
    protected $typeInstance;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master';
    protected $primaryKey = 'master_id';
    /**
     * The attributes that are mass assignable.
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
        'api_token',
        'token',
        'master_actors',
        'master_scriptwriters',
        'master_musiccomposers',
        'master_studios',
        'master_language',
        'master_type',
        'master_vt18',
        'master_bn', // PWS#13-bn
        'master_is_visible',
        'url_key',
        'master_description',
        'short_description',
        'meta_keywords',
        'meta_title',
        'meta_description',
        'master_trama', // PWS#13-trama
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        // 'password',
        // 'api_token',
        // 'remember_token',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \PSW\Cinema\Film\Database\Factories\MasterFactory
     */
    protected static function newFactory()
    {
        return MasterFactory::new ();
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    // public function sendPasswordResetNotification($token): void
    // {
    //     $this->notify(new CustomerResetPassword($token));
    // }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    // public function getJWTIdentifier()
    // {
    //     return $this->getKey();
    // }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    // public function getJWTCustomClaims(): array
    // {
    //     return [];
    // }

    /**
     * Get image url for the customer profile.
     *
     * @return string|null
     */
    public function getImageUrlAttribute()
    {
        return $this->image_url();
    }

    /**
     * Get type instance.
     *
     * @return AbstractType
     *
     * @throws \Exception
     */
    public function getTypeInstance(): AbstractType
    {
        if ($this->typeInstance) {
            return $this->typeInstance;
        }


        //$this->typeInstance = app(config('product_types.simple.class'));

        // if (! $this->typeInstance instanceof AbstractType) {
        //     throw new Exception("Please ensure the product type simple is configured in your application.");
        // }

        $this->typeInstance->setProduct($this);

        return $this->typeInstance;
    }
    /**
     * Get the customer full name.
     *
     * @return string
     */
    // public function getNameAttribute(): string
    // {
    //     return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    // }

    /**
     * Get image url for the customer image.
     *
     * @return string|null
     */
    public function image_url()
    {
        if (! $this->image) {
            return;
        }

        return Storage::url($this->image);
    }
    /**
     * The images that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(MasterImageProxy::modelClass(), 'master_id')
            ->orderBy('position');
    }
       /**
     * The images that belong to the product.
     *
     * @return string
     */
    public function getBaseImageUrlAttribute()
    {
        $image = $this->images()
            ->first();

        return $image->url ?? null;
    }


}
