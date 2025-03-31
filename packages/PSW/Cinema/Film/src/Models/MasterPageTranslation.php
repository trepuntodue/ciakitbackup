<?php

namespace PSW\Cinema\Film\Models;

use Illuminate\Database\Eloquent\Model;
use PSW\Cinema\Film\Contracts\MasterPageTranslation as MasterPageTranslationContract;

class MasterPageTranslation extends Model implements MasterPageTranslationContract
{
    public $timestamps = false;

    protected $fillable = [
        //'master_type'
    ];
}