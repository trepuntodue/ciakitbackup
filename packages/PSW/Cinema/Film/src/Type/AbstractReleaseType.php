<?php

namespace PSW\Cinema\Film\Type;

use Illuminate\Support\Facades\Storage;

use Webkul\Customer\Repositories\CustomerGroupRepository;
use PSW\Cinema\Film\Facades\ReleaseImage;
use PSW\Cinema\Film\Repositories\ReleaseImageRepository;
use PSW\Cinema\Film\Repositories\ReleaseRepository;
use PSW\Cinema\Film\Repositories\ReleaseMediaRepository;


abstract class AbstractReleaseType
{
    /**
     * Product instance.
     *
     * @var \PSW\Cinema\Film\Models\ReleasesPage
     */
    protected $release;

    
    /**
     * Create a new product type instance.
     *
     * @param  \PSW\Cinema\Film\Repositories\ReleaseRepository   $releaseRepository
     * @param  \PSW\Cinema\Film\Repositories\ReleaseImageRepository  $releaseImageRepository
     * @return void
     */
    public function __construct(
        protected ReleaseRepository $releaseRepository,
        protected ReleaseImageRepository $releaseImageRepository,
        protected ReleaseMediaRepository $releaseMediaRepository,
    )
    {
    }

    /**
     * Create product.
     *
     * @param  array  $data
     * @return \PSW\Cinema\Film\Contracts\ReleasesPage
     */
    public function create(array $data)
    {
        return $this->releaseRepository->getModel()->create($data);
    }

    /**
     * Update product.
     *
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \PSW\Cinema\Film\Contracts\ReleasesPage
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        die("passo da uodas");
        $releaseRepository = $this->releaseRepository->find($id);

        $releaseRepository->update($data);

        request()->file($attribute->code)->store('release/' . $release->id);
        $this->releaseImageRepository->uploadImages($data, $release);
        
       
        return $release;
    }
    /**
     * Specify type instance product.
     *
     * @param  \PSW\Cinema\Film\Contracts\ReleasesPage  $release
     * @return \PSW\Cinema\Film\Type\AbstractReleaseType
     */
    public function setProduct($release)
    {
        die("setProduct da uodas");
        $this->release = $release;

        return $this;
    }

}
