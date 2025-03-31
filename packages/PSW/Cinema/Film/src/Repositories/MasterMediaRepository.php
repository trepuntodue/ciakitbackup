<?php

namespace PSW\Cinema\Film\Repositories;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PSW\Cinema\Core\Eloquent\Repository;
use PSW\Cinema\Film\Type\AbstractType;

class MasterMediaRepository extends Repository 
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model()
    {
        /**
         * This repository is extended to `MasterImageRepository` 
         * repository.
         *
         * And currently no model is assigned to this repo.
         */
    }

    /**
     * Get product directory.
     *
     * @param  \PSW\Cinema\Film\Contracts\Master $master
     * @return string
     */
    public function getProductDirectory($master): string
    {
        // echo '';print_r($master);
        // die();
        return 'master/' . $master->id;
    }

    /**
     * Upload.
     *
     * @param  array  $data
     * @param  \PSW\Cinema\Film\Contracts\Master $master
     * @param  string  $uploadFileType
     * @return void
     */
    public function upload($data, $master, string $uploadFileType): void
    {
        /**
         * Previous model ids for filtering.
         */
        // echo '';print_r($master);
        // die();
        $previousIds = $this->resolveFileTypeQueryBuilder($master, $uploadFileType)->pluck('id');

        if (
            isset($data[$uploadFileType]['files'])
            && $data[$uploadFileType]['files']
        ) {
            foreach ($data[$uploadFileType]['files'] as $indexOrModelId => $file) {
                echo $indexOrModelId.'<br/>';
                if ($file instanceof UploadedFile) {
                    //inserisco valori nel
                    $this->create([
                        'type'       => $uploadFileType,
                        'path'       => $file->store($this->getProductDirectory($master)),
                        'master_id' => $master->id,
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
            echo '<pre>';print_R($previousIds);
            die();
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
     * @param  \PSW\Cinema\Film\Contracts\Master $master
     * @param  string  $uploadFileType
     * @return mixed
     *
     * @throws \Exception
     */
    private function resolveFileTypeQueryBuilder($master, string $uploadFileType)
    {
        if ($uploadFileType === 'images') {
            return $master->images();
        }

        throw new Exception('Unsupported file type.');
    }
}
