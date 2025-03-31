<?php

namespace PSW\Cinema\Film\Helpers;

use PSW\Cinema\Film\Type\AbstractReleaseType;

use Illuminate\Support\Facades\Storage;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use PSW\Cinema\Film\Facades\ReleaseImage;
use PSW\Cinema\Film\Repositories\ReleaseImageRepository;
use PSW\Cinema\Film\Repositories\ReleaseRepository;
use PSW\Cinema\Film\Repositories\ReleaseMediaRepository;

class AbstractRelease extends AbstractReleaseType
{
    protected $release;

    public function __construct(
        protected ReleaseRepository $releaseRepository,
        //protected ReleaseImageRepository $releaseImageRepository,
        //protected ReleaseMediaRepository $releaseImageRepository,
    )
    {
    }


    public function update(array $data, $id, $attribute = 'id')
    {
        die("updateasdas");
        $releaseRepository = $this->releaseRepository->find($id);

        $releaseRepository->update($data);
       // die();

        //request()->file($data['images']['files'][0])->store('release/' . $release->id);
       // $this->releaseImageRepository->uploadImages($data, $release);
        
       
        return $release;
    }
}
