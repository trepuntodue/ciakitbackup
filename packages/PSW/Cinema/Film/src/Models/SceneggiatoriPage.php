<?php

namespace PSW\Cinema\Film\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use PSW\Cinema\Film\Contracts\SceneggiatoriPage as SceneggiatoriContract;
use PSW\Cinema\Film\Database\Factories\SceneggiatoriFactory;
use Illuminate\Database\Eloquent\Model;



class SceneggiatoriPage extends Model implements SceneggiatoriContract
{
   

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sceneggiatori';
    /**
     * The attributes that are mass assignable.
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
     * @return \Webkul\Film\Database\Factories\SceneggiatoriFactory
     */
    protected static function newFactory()
    {
        return SceneggiatoriFactory::new ();
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
