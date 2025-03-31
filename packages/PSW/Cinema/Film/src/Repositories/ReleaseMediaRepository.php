<?php

namespace PSW\Cinema\Film\Repositories;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PSW\Cinema\Core\Eloquent\Repository;
use PSW\Cinema\Film\Type\AbstractReleaseType;

class ReleaseMediaRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model()
    {
        /**
         * This repository is extended to `ReleaseImageRepository`
         * repository.
         *
         * And currently no model is assigned to this repo.
         */
    }

    /**
     * Get product directory.
     *
     * @param  \PSW\Cinema\Film\Contracts\Release $release
     * @return string
     */
    public function getProductDirectory($release): string
    {
        die("passo da qui get");
        return 'release/' . $release->id;
    }

    /**
     * Upload.
     *
     * @param  array  $data
     * @param  \PSW\Cinema\Film\Contracts\Release $release
     * @param  string  $uploadFileType
     * @return void
     */
    public function upload($data, $release, string $uploadFileType): void
    {
        echo 'entro nella query'.$uploadFileType;
        echo '<pre>';print_r($data);
        die("entro uploads");
        /**
         * Previous model ids for filtering.
         */
        $previousIds = $this->resolveFileTypeQueryBuilder($release, $uploadFileType)->pluck('id');

        if (
            isset($data[$uploadFileType]['files'])
            && $data[$uploadFileType]['files']
        ) {
            foreach ($data[$uploadFileType]['files'] as $indexOrModelId => $file) {
                if ($file instanceof UploadedFile) {
                    $this->create([
                        'type'       => $uploadFileType,
                        'path'       => $file->store($this->getProductDirectory($release)),
                        'release_id' => $release->id,
                        'position'   => $indexOrModelId,
                    ]);
                } else {
                    /**
                     * Filter out existing models because new model positions are already setuped by index.
                     */
                    if (
                        isset($data[$uploadFileType]['positions'])
                        && $data[$uploadFileType]['positions']
                    ) {
                        $positions = collect($data[$uploadFileType]['positions'])->keys()->filter(function ($position) {
                            return is_numeric($position);
                        });

                        $this->update([
                            'position' => $positions->search($indexOrModelId),
                        ], $indexOrModelId);
                    }

                    if (is_numeric($index = $previousIds->search($indexOrModelId))) {
                        $previousIds->forget($index);
                    }
                }
            }
        }

        foreach ($previousIds as $indexOrModelId) {
            if ($model = $this->find($indexOrModelId)) {
                Storage::delete($model->path);

                $this->delete($indexOrModelId);
            }
        }
    }

    /**
     * Resolve file type query builder.
     *
     * @param  \PSW\Cinema\Film\Contracts\Release $release
     * @param  string  $uploadFileType
     * @return mixed
     *
     * @throws \Exception
     */
    private function resolveFileTypeQueryBuilder($release, string $uploadFileType)
    {
        die("resolvo");
        if ($uploadFileType === 'images') {
            return $release->images();
        }

        throw new Exception('Unsupported file type.');
    }
}
