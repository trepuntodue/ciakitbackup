<?php

namespace PSW\Cinema\Film\Type;

class SimpleRelease extends AbstractReleaseType
{
    /**
     * These blade files will be included in product edit page.
     *
     * @var array
     */
    protected $additionalViews = [
        'admin::cinema.release.accordians.images',
    ];

    /**
     * Show quantity box.
     *
     * @var bool
     */
    protected $showQuantityBox = true;

    /**
     * Return true if this product type is saleable. Saleable check added because
     * this is the point where all parent product will recall this.
     *
     * @return bool
     */
    

    /**
     * Have sufficient quantity.
     *
     * @param  int  $qty
     * @return bool
     */
   

    /**
     * Get product maximum price.
     *
     * @return float
     */
  
}
