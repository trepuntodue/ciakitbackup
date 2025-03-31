<?php

use PSW\Cinema\Film\Facades\MasterImage;
use PSW\Cinema\Film\Facades\ReleaseImage;


if (! function_exists('releaseimage')) {
    function releaseimage() {
        return app()->make(ReleaseImage::class);
    }
}
if (! function_exists('masterimage')) {
    function masterimage() {
        return app()->make(MasterImage::class);
    }
}

?>