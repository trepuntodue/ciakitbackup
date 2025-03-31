<?php

namespace PSW\Cinema\Film\Repositories;

use Illuminate\Container\Container as App;
use PSW\Cinema\Film\Repositories\ReleaseMediaRepository;
use PSW\Cinema\Film\Repositories\ReleaseRepository;


class ReleaseImageRepository extends ReleaseMediaRepository
{
    /**
     * Create a new repository instance.
     *
     * @param  \PSW\Cinema\Film\Repositories\ReleaseMediaRepository  $releaseMediaRepository
     * @param  \Illuminate\Container\Container  $app
     * @return void
     */
    public function __construct(
        protected ReleaseMediaRepository $releaseMediaRepository,
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
        return \PSW\Cinema\Film\Contracts\ReleaseImage::class;
    }

    /**
     * Upload images.
     *
     * @param  array  $data
     * @param  \PSW\Cinema\Film\Models\Release  $release
     * @return void
     */
    public function uploadImages($data, $release): void
    {
        die("uploadImages");
        $this->upload($data, $release, 'images');

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
        die("uploadImagesVariant");
        foreach ($variants as $variantsId => $variantData) {
            $release = $this->releaseMediaRepository->find($variantsId);

            if (!$release) {
                break;
            }

            $this->upload($variantData, $release, 'images');
        }
    }
}
