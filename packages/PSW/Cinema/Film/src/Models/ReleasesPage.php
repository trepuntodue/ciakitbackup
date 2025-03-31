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
use PSW\Cinema\Film\Contracts\ReleasesPage as ReleaseContract;
use PSW\Cinema\Film\Database\Factories\ReleaseFactory;
use PSW\Cinema\Film\Type\AbstractReleaseType;

class ReleasesPage extends Model implements ReleaseContract
{
    use HasFactory;
/**
     * The type of product.
     *
     * @var \PSW\Cinema\Film\Type\AbstractReleaseType
     */
    protected $typeInstance;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'releases';
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'customer_id',
        'master_id',
        'original_title',
        'other_title',
        'release_year',
        'release_distribution',
        'releasetype',
        'country',
        'language',
        'release_status',
        'default_release',
        'release_featured',
        'url_key',
        'release_vt18',
        'release_note',
        'release_description', 
        'short_description',
        'meta_keywords',
        'meta_title',
        'meta_description',
        'api_token',
        'token',
        'formato', // PWS#02-23 // PWS#rel-edit
        'aspect_ratio', // PWS#02-23 // PWS#rel-edit
        'camera_format', // PWS#02-23 // PWS#rel-edit
        'region_code', // PWS#02-23 // PWS#rel-edit
        'tipologia', // PWS#02-23 // PWS#rel-edit
        'canali_audio', // PWS#02-23 // PWS#rel-edit
        'subtitles', // PWS#02-23 // PWS#rel-edit
        'durata', // PWS#02-23 // PWS#rel-edit
        'contenuti_speciali', // PWS#02-23 // PWS#rel-edit
        'numero_catalogo', // PWS#02-23 // PWS#rel-edit
        'barcode', // PWS#02-23 // PWS#rel-edit
        'crediti', // PWS#02-23 // PWS#rel-edit
        'descrizione', // PWS#02-23 // PWS#rel-edit
        'poster_tipo', // PWS#video-poster // PWS#rel-edit
        'poster_formato', // PWS#video-poster // PWS#rel-edit
        'poster_misure', // PWS#video-poster // PWS#rel-edit
        'illustratore', // PWS#video-poster // PWS#rel-edit
        'stampatore', // PWS#video-poster // PWS#rel-edit
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
     * @return \PSW\Cinema\Film\Database\Factories\ReleaseFactory
     */
    protected static function newFactory()
    {
        return ReleaseFactory::new ();
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
     * @return AbstractReleaseType
     *
     * @throws \Exception
     */
    public function getTypeInstance(): AbstractReleaseType
    {
        if ($this->typeInstance) {
            return $this->typeInstance;
        }


        //$this->typeInstance = app(config('product_types.simple.class'));

        // if (! $this->typeInstance instanceof AbstractReleaseType) {
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
        return $this->hasMany(ReleaseImageProxy::modelClass(), 'release_id')
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

    /**
     * The related release that belong to the release.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function related_release(): BelongsToMany
    {
        return $this->belongsToMany(static::class, 'release_id')->limit(4);
    }

    
    
}
