<?php
namespace PSW\Cinema\Film\Repositories;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PSW\Cinema\Core\Eloquent\Repository;
use PSW\Cinema\Film\Facades\ReleaseImage;
use PSW\Cinema\Film\Repositories\ReleaseRepository;
use PSW\Cinema\Film\Repositories\ReleaseImageRepository;
use PSW\Cinema\Film\Repositories\ReleaseMediaRepository;
use PSW\Cinema\Film\Models\ReleasesPage;
use Illuminate\Http\UploadedFile;
use PSW\Cinema\Film\Type\AbstractReleaseType;
use PSW\Cinema\Film\Helpers\AbstractRelease;
use Illuminate\Support\Facades\DB;



class ReleaseRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        //return 'PSW\Cinema\Film\Contracts\ReleasesPage';
      // return ReleasePage::class;
       return 'PSW\Cinema\Film\Contracts\ReleasesPage';
    }

    /**
     * @param  array  $data
     * @return \PSW\Cinema\Film\Contracts\ReleasesPage
     */
    public function create(array $data)
    {
        Event::dispatch('film.release.create.before');

        $model = $this->getModel();

        foreach (core()->getAllLocales() as $locale) {
            foreach ($model->translatedAttributes as $attribute) {
                if (isset($data[$attribute])) {
                    $data[$locale->code][$attribute] = $data[$attribute];
                }
            }

            $data[$locale->code]['html_content'] = str_replace('=&gt;', '=>', $data[$locale->code]['html_content']);
        }

        $page = parent::create($data);

        $page->channels()->sync($data['channels']);

        Event::dispatch('film.release.create.after', $page);

        return $page;
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \PSW\Cinema\Film\Contracts\ReleasesPage
     */
    public function update(array $data, $id, $attribute = "id")
    {
        // echo '<pre>'; print_r($data);
        // echo $id.'---'.$attribute;
        //  echo '<pre>';
        // //
        //  print_r($data);
        // die("4");
        //$page = $this->find($id);
        // echo '<pre>'; print_r($data);
         //die("ottimo");
        Event::dispatch('film.release.update.before', $id);
        foreach ($data as $key => $value) { // PWS#chiusura
          if(!is_array($value) && strlen($value) == 0){
            $data[$key] = NULL;
          }
        }
        
        if(isset($data['language'])){ // PWS#chiusura
          $language = $data['language'];
          $data['language'] = NULL;
        } else{
          $language = array();
          $data['language'] = NULL;
        }
        // $locale = isset($data['locale']) ? $data['locale'] : app()->getLocale();

        // $data[$locale]['html_content'] = str_replace('=&gt;', '=>', $data[$locale]['html_content']);

        // parent::update($data, $id, $attribute);
        //$release = DB::table('releases')->where('id', '=', $id)->get();
        $release = $this->findOrFail($id);
        $release = parent::update($data, $id);

        $languages = array(); // PWS#chiusura
        foreach ($language as $key => $lang) {
          array_push($languages,['release_id' => $id, 'elemento_id' => (int) $lang, 'tipo_relazione' => config('constants.release.relazioni.lingua')]);
        }
        if(count($languages)){
          DB::table('release_relazioni')
            ->where('release_id',$id)
            ->where('tipo_relazione',config('constants.release.relazioni.lingua'))
            ->delete();
          DB::table('release_relazioni')->insert($languages);
        } // PWS#chiusura

        //$release = $this->uploadImages($data, $release, 'images');
       // $page->channels()->sync($data['channels']);

        Event::dispatch('film.release.update.after', $release);

        return $release;
    }

    /**
     * Checks slug is unique or not based on locale
     *
     * @param  int  $id
     * @param  string  $urlKey
     * @return bool
     */
    public function isUrlKeyUnique($id, $urlKey)
    {
        $exists = ReleasePageTranslationProxy::modelClass()::where('id', '<>', $id)
            ->limit(1)
            ->select(\DB::raw(1))
            ->exists();

        return $exists ? false : true;
    }

    /**
     * Retrive category from slug
     *
     * @param  string  $urlKey
     * @return \PSW\Cinema\Film\Contracts\ReleasesPage|\Exception
     */
    public function findByUrlKeyOrFail($urlKey)
    {
        $page = $this->model->whereTranslation('id', $urlKey)->first();

        if ($page) {
            return $page;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $urlKey
        );
    }
    public function related_release()
    {
        return $this->release->related_release()->get();
    }
    public function uploadImages($data, $release, string $uploadFileType): void
    {
       // die("ecco");
       // die($uploadFileType);
        /**
         * Previous model ids for filtering.
         */
        // echo '<pre>';print_r($master);
        // die('uploads mediarepository');
        $previousIds = $this->resolveFileTypeQueryBuilder($release, $uploadFileType)->pluck('id');
     // echo '<pre>';print_r($release);
    //echo '<pre>'; print_r($data[$uploadFileType]['files']);

        if (
            isset($data[$uploadFileType]['files'])
            && $data[$uploadFileType]['files']
        ) {
            foreach ($data[$uploadFileType]['files'] as $indexOrModelId => $file) {
              //  echo $file.'<br/>';
              //  echo '<pre>';print_r($release);
               // echo '1 '.$uploadFileType;
               // echo $file->store('release/'.$release->id);
              //  echo '3 '.$release->id.' 4 '.$indexOrModelId;
                //die();

                if ($file instanceof UploadedFile) {
                    echo 'entro nella query'.$uploadFileType;
                    //die("entro");
                    DB::table('release_images')->insert([
                            'type'       => $uploadFileType,
                            'path'       => $file->store('release/'.$release->id),
                            'release_id' => $release->id,
                            'position'   => $indexOrModelId,
                        ]);
        //die();
                    // $this->create([
                    //     'type'       => $uploadFileType,
                    //     'path'       => $file->store('release/'.$master->master_id),
                    //     'master_id' => $master->master_id,
                    //     'position'   => $indexOrModelId,
                    // ]);
                } else {
                    // echo 'entro nell filtro';

                     //die();
                    // echo 'pos ';print_r($data[$uploadFileType]['positions']);
                    /**
                     * Filter out existing models because new model positions are already setuped by index.
                     */
                    if (
                        isset($data[$uploadFileType]['positions'])
                        && $data[$uploadFileType]['positions']
                    ) {
           // echo 'position ';print_r($data[$uploadFileType]['positions']);
   // $positions = collect(DB::table('master_images')->select('position')->where('position', '=', $data[$uploadFileType]['positions'])->get());
                        $positions = collect($data[$uploadFileType]['positions'])->keys()->filter(function ($position) {
                            return is_numeric($position);
                        });
                        // echo '1<pre>';
                        // print_R($master->master_id);
                        // echo '<br/>2<pre>';
                        // print_r($data[$uploadFileType]['positions']);
                        // echo '3<pre>';
                        // echo $positions->search($indexOrModelId);
                     // die();
                     DB::table('release_images')->where('release_id', '=',$release->release_id )->where('position','=',$previousIds->search($indexOrModelId))->update(['position' => $previousIds->search($indexOrModelId)]);
                        // $this->update([
                        //     'position' => $positions->search($indexOrModelId),
                        // ], $indexOrModelId);
                    }
                //echo $positions->search($indexOrModelId);
                    if (is_numeric($index = $previousIds->search($indexOrModelId))) {
                        $previousIds->forget($index);
                    }
                }
            }
          // echo '<pre>';print_R($previousIds);

        }
         //echo '<pre>';print_r($previousIds);
        //  echo 'delete';

        foreach ($previousIds as $indexOrModelId) {
            //echo $indexOrModelId;
            //$model = $this->find($indexOrModelId);
            //echo $indexOrModelId;

            $model=DB::table('release_images')->select('path','position')->where('id', '=',$indexOrModelId)->get();
           // echo '<pre>';print_r($model[0]->path);
            // if ($model = $this->find($indexOrModelId)) {

            if ($model[0]->path){

               Storage::delete($model[0]->path);

                //$model->delete($indexOrModelId);
                $del=DB::table('release_images')->where('id', '=',$indexOrModelId )->delete();
                //echo 'delete';
               // die("delete");
             //  echo 'Cancello<pre>';print_r($del);

           }
        }
        //die();

    }
    private function resolveFileTypeQueryBuilder($release, string $uploadFileType)
    {
        // echo '<pre>';
        // print_r($release);
        // die("ddd");
        if ($uploadFileType === 'images') {
            return $release->images();
        }
        throw new Exception('Unsupported file type.');
    }



    /**
     * Search product by attribute.
     *
     * @param  string  $term
     * @return \Illuminate\Support\Collection
     */
    public function searchProductByAttribute($term)
    {
        $channel = core()->getRequestedChannelCode();

        $locale = core()->getRequestedLocaleCode();

        if (config('scout.driver') == 'algolia') {
            $results = app(ProductFlatRepository::class)->getModel()::search('query', function ($searchDriver, string $query, array $options) use ($term, $channel, $locale) {
                $queries = explode('_', $term);

                $options['similarQuery'] = array_map('trim', $queries);

                $searchDriver->setSettings([
                    'attributesForFaceting' => [
                        'searchable(locale)',
                        'searchable(channel)',
                    ],
                ]);

                $options['facetFilters'] = ['locale:' . $locale, 'channel:' . $channel];

                return $searchDriver->search($query, $options);
            })
                ->where('status', 1)
                ->where('visible_individually', 1)
                ->orderBy('product_id', 'desc')
                ->paginate(16);
        } elseif (config('scout.driver') == 'elastic') {
            $queries = explode('_', $term);

            $results = app(ProductFlatRepository::class)->getModel()::search(implode(' OR ', $queries))
                ->where('status', 1)
                ->where('visible_individually', 1)
                ->where('channel', $channel)
                ->where('locale', $locale)
                ->orderBy('product_id', 'desc')
                ->paginate(16);
        } else {
            $results = app(ProductFlatRepository::class)->scopeQuery(function ($query) use ($term, $channel, $locale) {

                $query = $query->distinct()
                    ->addSelect('product_flat.*')
                    ->where('product_flat.channel', $channel)
                    ->where('product_flat.locale', $locale)
                    ->whereNotNull('product_flat.url_key');

                if (! core()->getConfigData('catalog.products.homepage.out_of_stock_items')) {
                    $query = $this->checkOutOfStockItem($query);
                }

                return $query->where('product_flat.status', 1)
                    ->where('product_flat.visible_individually', 1)
                    ->where(function ($subQuery) use ($term) {
                        $queries = explode('_', $term);

                        foreach (array_map('trim', $queries) as $value) {
                            $subQuery->orWhere('product_flat.name', 'like', '%' . urldecode($value) . '%')
                                ->orWhere('product_flat.short_description', 'like', '%' . urldecode($value) . '%');
                        }
                    })
                    ->orderBy('product_id', 'desc');
            })->paginate(16);
        }

        return $results;
    }
}
