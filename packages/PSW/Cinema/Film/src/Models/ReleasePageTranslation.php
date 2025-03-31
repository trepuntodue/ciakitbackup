<?php

namespace PSW\Cinema\Film\Models;

use Illuminate\Database\Eloquent\Model;
use PSW\Cinema\Film\Contracts\ReleasePageTranslation as ReleasePageTranslationContract;

class ReleasePageTranslation extends Model implements ReleasePageTranslationContract
{
    public $timestamps = false;

    protected $fillable = [
        //'master_type'
    ];
}