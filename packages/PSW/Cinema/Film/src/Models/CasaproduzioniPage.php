<?php

namespace PSW\Cinema\Film\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use PSW\Cinema\Film\Contracts\CasaproduzioniPage as CasaproduzioniContract;
use PSW\Cinema\Film\Database\Factories\CasaproduzioniFactory;
use Illuminate\Database\Eloquent\Model;



class CasaproduzioniPage extends Model implements CasaproduzioniContract
{
   

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'casaproduzione';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'casa_nome',
        'status',

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
     * @return \PSW\Cinema\Film\Database\Factories\CasaproduzioniFactory
     */
    protected static function newFactory()
    {
        return CasaproduzioniFactory::new ();
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
    // public function image_url()
    // {
    //     if (! $this->image) {
    //         return;
    //     }

    //     return Storage::url($this->image);
    // }

    
}
