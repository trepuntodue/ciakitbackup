<?php

namespace PSW\Cinema\Film\CacheFilters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Large implements FilterInterface
{
    /**
     * Apply filter.
     *
     * @param  \Intervention\Image\Image  $image
     * @return \Intervention\Image\Image
     */
    public function applyFilter(Image $image)
    {
        $width =600;

        $height =900;

        return $image->resize(null, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
    }
}
