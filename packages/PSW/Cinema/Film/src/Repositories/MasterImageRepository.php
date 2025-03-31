<?php

namespace PSW\Cinema\Film\Repositories;

use Illuminate\Container\Container as App;
use PSW\Cinema\Film\Repositories\MasterMediaRepository;
use PSW\Cinema\Film\Repositories\MastersRepository;

class MasterImageRepository extends MasterMediaRepository
{
    /**
     * Create a new repository instance.
     *
     * @param  \PSW\Cinema\Film\Repositories\MasterMediaRepository  $masterMediaRepository
     * @param  \Illuminate\Container\Container  $app
     * @return void
     */
    public function __construct(
        protected MasterMediaRepository $masterMediaRepository,
        App $app
    )
    {
        parent::__construct($app);
    }

    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return \PSW\Cinema\Film\Contracts\MasterImage::class;
    }

    /**
     * Upload images.
     *
     * @param  array  $data
     * @param  \PSW\Cinema\Film\Models\MasterPage  $master
     * @return void
     */
    public function uploadImages($data, $master): void
    {
        //die("PRIMA DI TUTTO MASTER");
        $this->upload($data, $master, 'images');

        if (isset($data['variants'])) {
            $this->uploadVariantImages($data['variants']);
        }
    }

    /**
     * Upload variant images.
     *
     * @param  array $variants
     * @return void
     */
    public function uploadVariantImages($variants): void
    {
        foreach ($variants as $variantsId => $variantData) {
            $master = $this->masterMediaRepository->find($variantsId);

            if (!$master) {
                break;
            }

            $this->upload($variantData, $master, 'images');
        }
    }
}
