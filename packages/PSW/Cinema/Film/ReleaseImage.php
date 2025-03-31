<?php

namespace PSW\Cinema\Film;

use Illuminate\Support\Facades\Storage;
use PSW\Cinema\Film\Helpers\AbstractRelease;
//use PSW\Cinema\Film\RepositoriesReleaseRepository;

use PSW\Cinema\Film\Repositories\ReleaseRepository;

class ReleaseImage extends AbstractRelease
{
    /**
     * Create a new helper instance.
     *
     * @param  \PSW\Cinema\Film\Repositories\ReleaseRepository  $releaseRepository
     * @return void
     */
    public function __construct(
        protected ReleaseRepository $releaseRepository
        )
    {
    }

    /**
     * Retrieve collection of gallery images.
     *
     * @param  \PSW\Cinema\Film\Contracts\Release $release
     * @return array
     */
    public function getGalleryImages($release)
    {
        static $loadedGalleryImages = [];

        if (! $release) {
            return [];
        }

        if (array_key_exists($release->id, $loadedGalleryImages)) {
            return $loadedGalleryImages[$release->id];
        }

        $images = [];

        foreach ($release->images as $image) {
            if (! Storage::has($image->path)) {
                continue;
            }

            $images[] = $this->getCachedImageUrls($image->path);
        }

        if (
            ! $release->parent_id
            && ! count($images)

        ) {
            $images[] = $this->getFallbackImageUrls();
        }

        /*
         * Product parent checked already above. If the case reached here that means the
         * parent is available. So recursing the method for getting the parent image if
         * images of the child are not found.
         */
        if (empty($images)) {
            $images = $this->getGalleryImages($release->parent);
        }

        return $loadedGalleryImages[$release->id] = $images;
    }

    /**
     * Get product varient image if available otherwise product base image.
     *
     * @param  \Webkul\Customer\Contracts\Wishlist  $item
     * @return array
     */
    public function getProductImage($item)
    {

            $release = $item->release;

        return $this->getProductBaseImage($release);
    }

    /**
     * This method will first check whether the gallery images are already
     * present or not. If not then it will load from the product.
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     * @param  array
     * @return array
     */
    public function getProductBaseImage($release, array $galleryImages = null)
    {
        static $loadedBaseImages = [];

        if ($release) {
            if (array_key_exists($release->id, $loadedBaseImages)) {
                return $loadedBaseImages[$release->id];
            }

            return $loadedBaseImages[$release->id] = $galleryImages
                ? $galleryImages[0]
                : $this->otherwiseLoadFromProduct($release);
        }
    }

    /**
     * Load product's base image.
     *
     * @param  \Webkul\Product\Contracts\Product|\Webkul\Product\Contracts\ProductFlat  $product
     * @return array
     */
    protected function otherwiseLoadFromProduct($release)
    {
        $images = $release ? $release->images : null;

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
