<?php

namespace PSW\Cinema\Film\Repositories;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PSW\Cinema\Core\Eloquent\Repository;
use PSW\Cinema\Film\Facades\MasterImage;
use PSW\Cinema\Film\Repositories\MastersRepository;
use PSW\Cinema\Film\Repositories\MasterImageRepository;
use PSW\Cinema\Film\Repositories\MasterMediaRepository;
use PSW\Cinema\Film\Models\MasterPage;
use Illuminate\Http\UploadedFile;
use PSW\Cinema\Film\Type\AbstractType;
use PSW\Cinema\Film\Helpers\AbstractMaster;
use Illuminate\Support\Facades\DB;

class MastersRepository extends Repository
{
    /**
     * Create a new controller instance.
     *
     * @param \PSW\Cinema\Film\Repositories\MastersRepository  $mastersRepository
     * @param  \PSW\Cinema\Film\Repositories\MasterMediaRepository  $masterImageRepository
     */
    // public function __construct(
    //     protected MastersRepository $mastersRepository,
    //     protected MasterMediaRepository $masterImageRepository
    //      )
    //  {
    //      $this->_config = request('_config');
    //  }
    // public function __construct(MasterPage $model)
    // {
    //     $this->model = $model;
    // }
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'PSW\Cinema\Film\Contracts\MasterPage';
    }

    /**
     * @param  array  $data
     * @return \Webkul\Film\Contracts\MasterPage
     */
    public function create($attributes)
    {
         // echo '<pre>'; print_r($attributes);
          //die("muori?");
         // if($attributes['type']!='images'){

        Event::dispatch('film.master.create.before');
       // $typeInstance = app(config('product_types.simple.class'));
        //$master = $typeInstance->create($attributes);
        $master = parent::create($attributes);

        //START PWS#7-2
        $master_id = DB::getPdo()->lastInsertId();
        if(isset($attributes['master_genres'])){
          $generi = explode(",",$attributes['master_genres']);
          $master_generi = array();
          foreach ($generi as $key => $gen) {
            array_push($master_generi,['master_id' => $master_id, 'elemento_id' => (int) $gen, 'tipo_relazione' => config('constants.master.relazioni.generi')]);
          }
          DB::table('master_relazioni')->insert(
             $master_generi
         );
        }

        if(isset($attributes['master_subgenres'])){
          $sottogeneri = explode(",",$attributes['master_subgenres']);
          $master_sottogeneri = array();
          foreach ($sottogeneri as $key => $subgen) {
            array_push($master_sottogeneri,['master_id' => $master_id, 'elemento_id' => (int) $subgen, 'tipo_relazione' => config('constants.master.relazioni.sottogeneri')]);
          }
          DB::table('master_relazioni')->insert(
             $master_sottogeneri
         );
        }

        if(isset($attributes['country'])){
          $countries = explode(",",$attributes['country']);
          $master_countries = array();
          foreach ($countries as $key => $country) {
            $country_id = DB::table('countries')
              ->where('code',$country)
              ->addSelect('id')
              ->first();
            array_push($master_countries,['master_id' => $master_id, 'elemento_id' => (int) $country_id->id, 'tipo_relazione' => config('constants.master.relazioni.countries')]);
          }
          DB::table('master_relazioni')->insert(
             $master_countries
         );
        }

        if(isset($attributes['master_director'])){
          $registi = explode(",",$attributes['master_director']);
          $master_registi = array();
          foreach ($registi as $key => $regista) {
            array_push($master_registi,['master_id' => $master_id, 'elemento_id' => (int) $regista, 'tipo_relazione' => config('constants.master.relazioni.registi')]);
          }
          DB::table('master_relazioni')->insert(
             $master_registi
         );
        }

        if(isset($attributes['master_scriptwriters'])){
          $sceneggiatori = explode(",",$attributes['master_scriptwriters']);
          $master_sceneggiatori = array();
          foreach ($sceneggiatori as $key => $sceneggiatore) {
            array_push($master_sceneggiatori,['master_id' => $master_id, 'elemento_id' => (int) $sceneggiatore, 'tipo_relazione' => config('constants.master.relazioni.sceneggiatori')]);
          }
          DB::table('master_relazioni')->insert(
             $master_sceneggiatori
         );
        }

        if(isset($attributes['master_musiccomposers'])){
          $compositori = explode(",",$attributes['master_musiccomposers']);
          $master_compositori = array();
          foreach ($compositori as $key => $compositore) {
            array_push($master_compositori,['master_id' => $master_id, 'elemento_id' => (int) $compositore, 'tipo_relazione' => config('constants.master.relazioni.compositori')]);
          }
          DB::table('master_relazioni')->insert(
             $master_compositori
         );
        }

        if(isset($attributes['master_studios'])){
          $casaproduziones = explode(",",$attributes['master_studios']);
          $master_casaproduzione = array();
          foreach ($casaproduziones as $key => $casaproduzione) {
            array_push($master_casaproduzione,['master_id' => $master_id, 'elemento_id' => (int) $casaproduzione, 'tipo_relazione' => config('constants.master.relazioni.casaproduzione')]);
          }
          DB::table('master_relazioni')->insert(
             $master_casaproduzione
         );
        }

        if(isset($attributes['master_actors'])){
          $attori = explode(",",$attributes['master_actors']);
          $master_attori = array();
          foreach ($attori as $key => $attore) {
            array_push($master_attori,['master_id' => $master_id, 'elemento_id' => (int) $attore, 'tipo_relazione' => config('constants.master.relazioni.attori')]);
          }
          DB::table('master_relazioni')->insert(
             $master_attori
         );
        }
        //END PWS#7-2

        //START PWS#10-lang
        if(isset($attributes['master_language'])){
          $lingue = explode(",",$attributes['master_language']);
          $master_lingue = array();
          foreach ($lingue as $key => $lingua) {
            array_push($master_lingue,['master_id' => $master_id, 'elemento_id' => (int) $lingua, 'tipo_relazione' => config('constants.master.relazioni.lingua')]);
          }
          DB::table('master_relazioni')->insert(
             $master_lingue
         );
        }
        //END PWS#10-lang

        Event::dispatch('film.master.create.after', $master);

        return $master;

    }

    /**
     * Update customer.
     *
     * @param  array  $attributes
     * @return mixed
     */
    // public function update(array $attributes, $id)
    public function update(array $data, $id, $attribute = 'id')
    {
        //echo '<pre>'; print_r($data);


        Event::dispatch('film.master.update.before');
        $master = $this->findOrFail($id);
        //START PWS#7-2
        $master_id = $id;
        if(isset($data['master_genres'])){
          $generi = explode(",",$data['master_genres']);
          $master_generi = array();
          foreach ($generi as $key => $gen) {
            array_push($master_generi,['master_id' => $master_id, 'elemento_id' => (int) $gen, 'tipo_relazione' => config('constants.master.relazioni.generi')]);
          }
          DB::table('master_relazioni')
            ->where('master_id',$id)
            ->where('tipo_relazione',config('constants.master.relazioni.generi'))
            ->delete();
          DB::table('master_relazioni')->insert(
             $master_generi
         );
        }

        if(isset($data['master_subgenres'])){
          $sottogeneri = explode(",",$data['master_subgenres']);
          $master_sottogeneri = array();
          foreach ($sottogeneri as $key => $subgen) {
            array_push($master_sottogeneri,['master_id' => $master_id, 'elemento_id' => (int) $subgen, 'tipo_relazione' => config('constants.master.relazioni.sottogeneri')]);
          }
          DB::table('master_relazioni')
            ->where('master_id',$id)
            ->where('tipo_relazione',config('constants.master.relazioni.sottogeneri'))
            ->delete();
          DB::table('master_relazioni')->insert(
             $master_sottogeneri
         );
       } else{ // START PWS#2-23/11
         DB::table('master_relazioni')
           ->where('master_id',$id)
           ->where('tipo_relazione',config('constants.master.relazioni.sottogeneri'))
           ->delete();
       } // END PWS#2-23/11

        if(isset($data['country'])){
          $countries = explode(",",$data['country']);
          $master_countries = array();
          foreach ($countries as $key => $country) {
            $country_id = DB::table('countries')
              ->where('code',$country)
              ->addSelect('id')
              ->first();
            array_push($master_countries,['master_id' => $master_id, 'elemento_id' => (int) $country_id->id, 'tipo_relazione' => config('constants.master.relazioni.countries')]);
          }
          DB::table('master_relazioni')
            ->where('master_id',$id)
            ->where('tipo_relazione',config('constants.master.relazioni.countries'))
            ->delete();
          DB::table('master_relazioni')->insert(
             $master_countries
         );
        }

        if(isset($data['master_director'])){
          $registi = explode(",",$data['master_director']);
          $master_registi = array();
          foreach ($registi as $key => $regista) {
            array_push($master_registi,['master_id' => $master_id, 'elemento_id' => (int) $regista, 'tipo_relazione' => config('constants.master.relazioni.registi')]);
          }
          DB::table('master_relazioni')
            ->where('master_id',$id)
            ->where('tipo_relazione',config('constants.master.relazioni.registi'))
            ->delete();
          DB::table('master_relazioni')->insert(
             $master_registi
         );
        }

        if(isset($data['master_scriptwriters'])){
          $sceneggiatori = explode(",",$data['master_scriptwriters']);
          $master_sceneggiatori = array();
          foreach ($sceneggiatori as $key => $sceneggiatore) {
            array_push($master_sceneggiatori,['master_id' => $master_id, 'elemento_id' => (int) $sceneggiatore, 'tipo_relazione' => config('constants.master.relazioni.sceneggiatori')]);
          }
          DB::table('master_relazioni')
            ->where('master_id',$id)
            ->where('tipo_relazione',config('constants.master.relazioni.sceneggiatori'))
            ->delete();
          DB::table('master_relazioni')->insert(
             $master_sceneggiatori
         );
        }

        if(isset($data['master_musiccomposers'])){
          $compositori = explode(",",$data['master_musiccomposers']);
          $master_compositori = array();
          foreach ($compositori as $key => $compositore) {
            array_push($master_compositori,['master_id' => $master_id, 'elemento_id' => (int) $compositore, 'tipo_relazione' => config('constants.master.relazioni.compositori')]);
          }
          DB::table('master_relazioni')
            ->where('master_id',$id)
            ->where('tipo_relazione',5)
            ->delete();
          DB::table('master_relazioni')->insert(
             $master_compositori
         );
        }

        if(isset($data['master_studios'])){
          $casaproduziones = explode(",",$data['master_studios']);
          $master_casaproduzione = array();
          foreach ($casaproduziones as $key => $casaproduzione) {
            array_push($master_casaproduzione,['master_id' => $master_id, 'elemento_id' => (int) $casaproduzione, 'tipo_relazione' => config('constants.master.relazioni.casaproduzione')]);
          }
          DB::table('master_relazioni')
            ->where('master_id',$id)
            ->where('tipo_relazione',config('constants.master.relazioni.casaproduzione'))
            ->delete();
          DB::table('master_relazioni')->insert(
             $master_casaproduzione
         );
        }

        if(isset($data['master_actors'])){
          $attori = explode(",",$data['master_actors']);
          $master_attori = array();
          foreach ($attori as $key => $attore) {
            array_push($master_attori,['master_id' => $master_id, 'elemento_id' => (int) $attore, 'tipo_relazione' => config('constants.master.relazioni.attori')]);
          }
          DB::table('master_relazioni')
            ->where('master_id',$id)
            ->where('tipo_relazione',config('constants.master.relazioni.attori'))
            ->delete();
          DB::table('master_relazioni')->insert(
             $master_attori
         );
        }
        //END PWS#7-2

        //START PWS#10-lang
        if(isset($data['master_language'])){
          $lingue = explode(",",$data['master_language']);
          $master_lingue = array();
          foreach ($lingue as $key => $lingua) {
            array_push($master_lingue,['master_id' => $master_id, 'elemento_id' => (int) $lingua, 'tipo_relazione' => config('constants.master.relazioni.lingua')]);
          }
          DB::table('master_relazioni')
            ->where('master_id',$id)
            ->where('tipo_relazione',config('constants.master.relazioni.lingua'))
            ->delete();
          DB::table('master_relazioni')->insert(
             $master_lingue
         );
        }
        //END PWS#10-lang

        $master = parent::update($data, $id);
        //$apple = new MasterMediaRepository();
       // $model = MasterPage::where()->first();
       // $master->update($data,(array) $attribute);
        //$master = MasterMediaRepository::update($data, $id, $attribute);
       //$master->getTypeInstance()->update($data, $id, $attribute);
        // $master= AbstractMaster::update($data, $id, $attribute);
        //update images
        // $master = parent::uploadImages($data, $master,'images');
        //$master = AbstractType::update($data, $id, $attribute);

      //$this->masterRepository->uploadImages($data, $master,'images');

        Event::dispatch('film.master.update.after', $master);

        return $master;
    }
    /**
     * Delete product.
     *
     * @param  int  $id
     * @return void
     */


    public function delete($id)
    {

        $master = $this->findOneByField('master_id',$id);

        Event::dispatch('cinema.master.delete.before', $id);
        $master->delete();

        Event::dispatch('cinema.master.delete.after', $id);
    }
    public function uploadImages($data, $master, string $uploadFileType): void
    {

        /**
         * Previous model ids for filtering.
         */
        // echo '<pre>';print_r($master);
        // die('uploads mediarepository');
        $previousIds = $this->resolveFileTypeQueryBuilder($master, $uploadFileType)->pluck('id');
      // echo '<pre>';print_r($previousIds);

        if (
            isset($data[$uploadFileType]['files'])
            && $data[$uploadFileType]['files']
        ) {
            foreach ($data[$uploadFileType]['files'] as $indexOrModelId => $file) {
                //echo $indexOrModelId.'<br/>';
            //echo '1 '.$uploadFileType.'2 '.$file->store('master/'.$master->master_id).'3 '.$master->master_id.' 4 '.$indexOrModelId;
           // die();
                if ($file instanceof UploadedFile) {
                    //echo 'entro nella query';
                    DB::table('master_images')->insert([
                            'type'       => $uploadFileType,
                            'path'       => $file->store('master/'.$master->master_id),
                            'master_id' => $master->master_id,
                            'position'   => $indexOrModelId,
                        ]);
        //die();
                    // $this->create([
                    //     'type'       => $uploadFileType,
                    //     'path'       => $file->store('master/'.$master->master_id),
                    //     'master_id' => $master->master_id,
                    //     'position'   => $indexOrModelId,
                    // ]);
                } else {
                    // echo 'entro nell filtro';
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
                     DB::table('master_images')->where('master_id', '=',$master->master_id )->where('position','=',$previousIds->search($indexOrModelId))->update(['position' => $previousIds->search($indexOrModelId)]);
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

            $model=DB::table('master_images')->select('path','position')->where('id', '=',$indexOrModelId)->get();
           // echo '<pre>';print_r($model[0]->path);
            // if ($model = $this->find($indexOrModelId)) {

            if ($model[0]->path){

               Storage::delete($model[0]->path);

                //$model->delete($indexOrModelId);
                $del=DB::table('master_images')->where('id', '=',$indexOrModelId )->delete();
                //echo 'delete';
               // die("delete");
             //  echo 'Cancello<pre>';print_r($del);

           }
        }
        //die();

    }
    private function resolveFileTypeQueryBuilder($master, string $uploadFileType)
    {
        if ($uploadFileType === 'images') {
            return $master->images();
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
