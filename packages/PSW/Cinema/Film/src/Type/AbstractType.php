<?php

namespace PSW\Cinema\Film\Type;

use Illuminate\Support\Facades\Storage;

use Webkul\Customer\Repositories\CustomerGroupRepository;
use PSW\Cinema\Film\Facades\MasterImage;
use PSW\Cinema\Film\Repositories\MasterImageRepository;
use PSW\Cinema\Film\Repositories\MastersRepository;
use PSW\Cinema\Film\Repositories\MasteMediaRepository;


abstract class AbstractType
{
    /**
     * Product instance.
     *
     * @var \PSW\Cinema\Film\Models\MasterPage
     */
    protected $master;

    
    /**
     * Create a new product type instance.
     *
     * @param  \PSW\Cinema\Film\Repositories\MastersRepository   $masterRepository
     * @param  \PSW\Cinema\Film\Repositories\MasterImageRepository  $masterImageRepository
     * @return void
     */
    public function __construct(
        protected MastersRepository $masterRepository,
        protected MasterImageRepository $masterImageRepository,
        protected MasteMediaRepository $masterMediaRepository,
    )
    {
    }

    

    /**
     * Create product.
     *
     * @param  array  $data
     * @return \PSW\Cinema\Film\Contracts\MasterPage
     */
    public function create(array $data)
    {
        return $this->masterRepository->getModel()->create($data);
    }

    /**
     * Update product.
     *
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \PSW\Cinema\Film\Contracts\MasterPage
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        die("2");
        $masterRepository = $this->masterRepository->find($id);

        $masterRepository->update($data);

        request()->file($attribute->code)->store('master/' . $master->master_id);
        $this->masterImageRepository->uploadImages($data, $master);
        
       
        return $master;
    }
    /**
     * Specify type instance product.
     *
     * @param  \PSW\Cinema\Film\Contracts\MasterPage  $master
     * @return \PSW\Cinema\Film\Type\AbstractType
     */
    public function setProduct($master)
    {
        $this->master = $master;

        return $this;
    }

}
