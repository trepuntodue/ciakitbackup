<?php

namespace PSW\Cinema\Film\Helpers;

use PSW\Cinema\Film\Type\AbstractType;

use Illuminate\Support\Facades\Storage;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use PSW\Cinema\Film\Facades\MasterImage;
use PSW\Cinema\Film\Repositories\MasterImageRepository;
use PSW\Cinema\Film\Repositories\MastersRepository;
use PSW\Cinema\Film\Repositories\MasteMediaRepository;

class AbstractMaster extends AbstractType
{
    protected $master;

    public function __construct(
        protected MastersRepository $masterRepository,
        //protected MasterImageRepository $masterImageRepository,
        //protected MasteMediaRepository $masterImageRepository,
    )
    {
    }


    public function update(array $data, $id, $attribute = 'id')
    {
        $masterRepository = $this->masterRepository->find($id);

        $masterRepository->update($data);
        // echo '<pre>';print_r($data);
        // echo $data['images']['files'][0];
       // die();

        //request()->file($data['images']['files'][0])->store('master/' . $master->master_id);
       // $this->masterImageRepository->uploadImages($data, $master);
        
       
        return $master;
    }
}
