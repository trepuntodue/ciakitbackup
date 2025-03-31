<?php

namespace PSW\Cinema\Film;

use Illuminate\Support\Facades\Storage;
use PSW\Cinema\Film\Helpers\AbstractMaster;
use PSW\Cinema\Film\Repositories\MastersRepository;

class MasterImage extends AbstractMaster
{
    /**
     * Create a new helper instance.
     *
     * @param  \PSW\Cinema\Film\Repositories\MastersRepository  $masterRepository
     * @return void
     */
    public function __construct(
        protected MastersRepository $masterRepository
        )
    {
    }
    public function monica($master){
        echo '<pre>';print_r($master);
        die();
    }
    /**
     * Retrieve collection of gallery images.
     *
     * @param  \PSW\Cinema\Film\Contracts\MasterPage $master
     * @return array
     */
    public function getGalleryImages($master)
    {
        static $loadedGalleryImages = [];
echo '<pre>';print_r($master);
die();
        if (! $master) {
            return [];
        }

        if (array_key_exists($master->master_id, $loadedGalleryImages)) {
            return $loadedGalleryImages[$master->master_id];
        }

        $images = [];

        foreach ($master->images as $image) {
            if (! Storage::has($image->path)) {
                continue;
            }

            $images[] = $this->getCachedImageUrls($image->path);
        }

        if (
                ! count($images)
        ) {
            $images[] = $this->getFallbackImageUrls();
        }

        /*
         * Product parent checked already above. If the case reached here that means the
         * parent is available. So recursing the method for getting the parent image if
         * images of the child are not found.
         */
        if (empty($images)) {
            $images = $this->getGalleryImages($master->parent);
        }

        return $loadedGalleryImages[$master->id] = $images;
    }

    /**
     * Get product varient image if available otherwise product base image.
     *
     * @param  \Webkul\Customer\Contracts\Wishlist  $item
     * @return array
     */
    public function getProductImage($item)
    {

            $master = $item->master;

        return $this->getProductBaseImage($master);
    }
    

    /**
     * This method will first check whether the gallery images are already
     * present or not. If not then it will load from the product.
     *
     * @param  \PSW\Cinema\Film\Contracts\Master $master
     * @param  array
     * @return array
     */
    public function getProductBaseImage($master, array $galleryImages = null)
    {
        static $loadedBaseImages = [];

        if ($master) {
            if (array_key_exists($master->id, $loadedBaseImages)) {
                return $loadedBaseImages[$master->id];
            }

            return $loadedBaseImages[$master->id] = $galleryImages
                ? $galleryImages[0]
                : $this->otherwiseLoadFromProduct($master);
        }
    }

    /**
     * Load product's base image.
     *
     * @param  \PSW\Cinema\Film\Contracts\Master  $master
     * @return array
     */
    protected function otherwiseLoadFromProduct($master)
    {
        $images = $master ? $master->images : null;

        return $images && $images->count()
            ? $this->getCachedImageUrls($images[0]->path)
            : $this->getFallbackImageUrls();
    }

    /**
     * Get cached urls configured for intervention package.
     *
     * @param  string  $path
     * @return array
     */
    private function getCachedImageUrls($path): array
    {
        if (! $this->isDriverLocal()) {
            return [
                'small_image_url'    => Storage::url($path),
                'medium_image_url'   => Storage::url($path),
                'large_image_url'    => Storage::url($path),
                'original_image_url' => Storage::url($path),
            ];
        }

        return [
            'small_image_url'    => url('cache/small/' . $path),
            'medium_image_url'   => url('cache/medium/' . $path),
            'large_image_url'    => url('cache/large/' . $path),
            'original_image_url' => url('cache/original/' . $path),
        ];
    }

    /**
     * Get fallback urls.
     *
     * @return array
     */
    private function getFallbackImageUrls(): array
    {
        return [
            'small_image_url'    => asset('vendor/webkul/ui/assets/images/product/small-product-placeholder.webp'),
            'medium_image_url'   => asset('vendor/webkul/ui/assets/images/product/meduim-product-placeholder.webp'),
            'large_image_url'    => asset('vendor/webkul/ui/assets/images/product/large-product-placeholder.webp'),
            'original_image_url' => asset('vendor/webkul/ui/assets/images/product/large-product-placeholder.webp'),
        ];
    }

    /**
     * Is driver local.
     *
     * @return bool
     */
    private function isDriverLocal(): bool
    {
        return Storage::getAdapter() instanceof \League\Flysystem\Local\LocalFilesystemAdapter;
    }
}
