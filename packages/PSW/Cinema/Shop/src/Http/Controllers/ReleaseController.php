<?php

namespace PSW\Cinema\Shop\Http\Controllers;

use Illuminate\Support\Facades\DB;
use PSW\Cinema\Film\Repositories\ReleaseRepository;
use Illuminate\Support\Facades\Auth; // PWS#13-release


class ReleaseController extends Controller
{
  /**
   * Create a new controller instance.
   *
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }
  /**
   * Display release of particular product.
   *
   * @param  string  $slug
   * @return \Illuminate\View\View
   */
  public function show($id, $slug)
  {
    //   echo 'slug '.$slug;

    $releases = DB::table('releases')->where('url_key', '=', $slug)->where('id', '=', $id)->first();
    $releases = DB::table('releases as ca')
      ->leftJoin('release_relazioni', 'ca.id', '=', 'release_relazioni.release_id') // PWS#chiusura-lang
      ->leftJoin('release_images', 'ca.id', '=', 'release_images.release_id')
      ->leftJoin('master', 'ca.master_id', '=', 'master.master_id')
      ->leftJoin('master_relazioni', 'ca.master_id', '=', 'master_relazioni.master_id')
      ->leftJoin('releaseType as rt', 'ca.releasetype', '=', 'rt.id') // PWS#02-23
      ->leftJoin('release_aspect_ratio as rar', 'ca.aspect_ratio', '=', 'rar.id') // PWS#02-23
      ->leftJoin('release_camera_format as rcf', 'ca.camera_format', '=', 'rcf.id') // PWS#02-23
      ->leftJoin('release_tipologia as rtip', 'ca.tipologia', '=', 'rtip.id') // PWS#02-23
      ->leftJoin('release_canali_audio as rca', 'ca.canali_audio', '=', 'rca.id') // PWS#02-23
      ->leftJoin('release_poster_tipo as rpt', 'ca.poster_tipo', '=', 'rpt.id') // PWS#video-poster
      ->leftJoin('release_poster_formato as rpf', 'ca.poster_formato', '=', 'rpf.id') // PWS#video-poster
      ->leftJoin('release_poster_misure as rpm', 'ca.poster_misure', '=', 'rpm.id') // PWS#video-poster
      ->leftJoin('release_formato as rf', 'ca.formato', '=', 'rf.id') // PWS#video-poster
      ->leftJoin('generi', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'generi.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
      })
      ->leftJoin('sottogeneri', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'sottogeneri.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.sottogeneri')));
      })
      ->leftJoin('countries', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'countries.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.countries')));
      })
      ->leftJoin('registi', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'registi.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.registi')));
      })
      ->leftJoin('sceneggiatori', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'sceneggiatori.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.sceneggiatori')));
      })
      ->leftJoin('compositori', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'compositori.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.compositori')));
      })
      ->leftJoin('casaproduzione', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'casaproduzione.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.casaproduzione')));
      })
      ->leftJoin('attori', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'attori.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.attori')));
      })
      ->leftJoin('language', function ($join) {
        $join->on('release_relazioni.elemento_id', '=', 'language.id');
        $join->on('release_relazioni.tipo_relazione', '=', DB::raw(config('constants.release.relazioni.lingua')));
      })
      ->addSelect(
        'ca.*',
        'master.*',
        'master.url_key AS master_url_key',
        'rt.name as release_type',
        'rar.nome as aspect_ratio',
        'rcf.nome as camera_format',
        'rpt.nome as poster_tipo',
        'rpf.nome as poster_formato',
        'rpm.nome as poster_misure',
        'rf.nome as formato',
        'rtip.nome as tipologia',
        'rca.nome as canali_audio',
        DB::raw(DB::getTablePrefix() . 'release_images.path as path'),
        DB::raw('GROUP_CONCAT(DISTINCT TRIM(' . DB::getTablePrefix() . 'generi.generi_name) SEPARATOR \', \') as genres_name'),
        DB::raw('GROUP_CONCAT(DISTINCT TRIM(' . DB::getTablePrefix() . 'registi.registi_nome_cognome) SEPARATOR \', \') as director_name'),
        DB::raw('GROUP_CONCAT(DISTINCT TRIM(' . DB::getTablePrefix() . 'sottogeneri.subge_name) SEPARATOR \', \') as subgenres_name'),
        DB::raw('GROUP_CONCAT(DISTINCT TRIM(' . DB::getTablePrefix() . 'generi.id) SEPARATOR \', \') as genres_ids'),
        DB::raw('GROUP_CONCAT(DISTINCT TRIM(' . DB::getTablePrefix() . 'registi.registi_nome_cognome) SEPARATOR \', \') as director_name'),
        DB::raw('GROUP_CONCAT(DISTINCT TRIM(' . DB::getTablePrefix() . 'sceneggiatori.scene_nome_cognome) SEPARATOR \', \') as sceneggiatori_name'),
        DB::raw('GROUP_CONCAT(DISTINCT TRIM(' . DB::getTablePrefix() . 'attori.attori_nome_cognome) SEPARATOR \', \') as attori_name'),
        DB::raw('GROUP_CONCAT(DISTINCT TRIM(' . DB::getTablePrefix() . 'compositori.compo_alias) SEPARATOR \', \') as compositori_name'),
        DB::raw('GROUP_CONCAT(DISTINCT TRIM(' . DB::getTablePrefix() . 'casaproduzione.casa_nome) SEPARATOR \', \') as casaproduzione_nome'),
        DB::raw('GROUP_CONCAT(DISTINCT TRIM(' . DB::getTablePrefix() . 'language.name) SEPARATOR \', \') as lingua'),
      )
      ->where('ca.url_key', '=', $slug)->where('ca.id', '=', $id)
      ->where('ca.release_status', DB::raw(1))
      ->where('ca.release_is_visible', DB::raw(1))
      ->groupBy('ca.id')
      ->first(); // PWS#frontend
    //  echo '<pre>';print_r($releases);

    $favorite = false;
    if(Auth::check()){
      $release_user_favorite = DB::table('release_user_favorite')
        ->where('release_id', '=', DB::raw($id))
        ->where('user_id', '=', DB::raw(Auth::id()))
        ->get()->first();

      if($release_user_favorite) $favorite = true;
    }

    $collection = false;
    if(Auth::check()){
      $release_user = DB::table('release_user')
        ->where('release_id', '=', DB::raw($id))
        ->where('user_id', '=', DB::raw(Auth::id()))
        ->get()->first();

      if($release_user) $collection = true;
    }

    // PWS#18-2
    $products = DB::table('products as ca')
      ->leftJoin('product_attribute_values as pav1', function ($join) use ($id) {
        $join->on('pav1.product_id', '=', 'ca.id');
        $join->on('pav1.attribute_id', '=', DB::raw(29));
      })
      ->leftJoin('product_attribute_values as pav2', function ($join) use ($id) {
        $join->on('pav2.product_id', '=', 'ca.id');
        $join->on('pav2.attribute_id', '=', DB::raw(3));
      })
      ->leftJoin('product_attribute_values as pav3', function ($join) use ($id) {
        $join->on('pav3.product_id', '=', 'ca.id');
        $join->on('pav3.attribute_id', '=', DB::raw(2));
      })
      ->leftJoin('product_attribute_values as pav4', function ($join) use ($id) {
        $join->on('pav4.product_id', '=', 'ca.id');
        $join->on('pav4.attribute_id', '=', DB::raw(11));
      })
      ->leftJoin('product_attribute_values as pav5', function ($join) use ($id) {
        $join->on('pav5.product_id', '=', 'ca.id');
        $join->on('pav5.attribute_id', '=', DB::raw(28));
      })
      ->leftJoin('product_attribute_values as pav6', function ($join) use ($id) {
        $join->on('pav6.product_id', '=', 'ca.id');
        $join->on('pav6.attribute_id', '=', DB::raw(29));
      })
      ->leftJoin('product_images as pi', function ($join) {
        $join->on('pi.product_id', '=', 'ca.id');
      })
      ->leftJoin('customers as c', function ($join) {
        $join->on('c.id', '=', 'pav5.integer_value');
      })
      ->leftJoin('releases as r', function ($join) {
        $join->on('r.id', '=', 'pav6.integer_value');
      })
      ->where('pav1.integer_value', '=', DB::raw($id))
      ->addSelect(
        'ca.*',
        'pav2.text_value AS url_key',
        'pav3.text_value AS product_name',
        'pav4.float_value AS price',
        'pav5.integer_value AS owner_id',
        'pav5.integer_value AS release_id',
        'c.first_name as owner_fname',
        'c.last_name as owner_lname',
        'r.release_distribution as casa_distribuzione',
        // DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'pi.path SEPARATOR "||") AS prod_images'),
        'pi.path as image_path',
      )
      ->groupBy('ca.id')
      ->get()->all();

    $images = array();
    $master_images = array();
    if($releases){
      $images = DB::table('release_images')->where('release_id', '=', $releases->id)->get()->all();
      // echo $releases->id;
      //    echo '<pre>';print_r($images);
      // die();
    }

    if($releases){
      $master_images = DB::table('master_images')->where('master_id', '=', $releases->master_id)->get()->all();
    }

    if($releases){
      $related_releases = DB::table('releases as ca')
      ->leftJoin('release_images', 'ca.id', '=', 'release_images.release_id')
      ->leftJoin('master_images', 'ca.master_id', '=', 'master_images.master_id')
      ->leftJoin('language', 'ca.language', '=', 'language.id')
      ->leftJoin('master', 'ca.master_id', '=', 'master.master_id')
      ->leftJoin('master_relazioni', 'ca.master_id', '=', 'master_relazioni.master_id')
      ->leftJoin('release_poster_formato as rpf', 'ca.poster_formato', '=', 'rpf.id')
      ->leftJoin('generi', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'generi.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
      })
      ->leftJoin('registi', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'registi.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.registi')));
      })
      ->leftJoin('countries', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'countries.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.countries')));
      })
      ->where(function($query){
        $query->where('release_images.position', 0)
        ->orWhereNull('release_images.id');
      })
      ->where('ca.release_status', DB::raw(1))
      ->where('ca.release_is_visible', DB::raw(1))
      ->where('ca.id', "!=", $id)
      ->where('ca.releasetype', "=", $releases->releasetype)
      ->where(function($query) use ($releases){
        $user = Auth::user();
        if(!Auth::check() || (Auth::check() && strtotime('18 years ago') < strtotime($user->date_of_birth) && $user->date_of_birth != '0000-00-00' && $user->date_of_birth != false) || (Auth::check() && ($user->date_of_birth == '0000-00-00' || $user->date_of_birth == false))){
          $query->where('master.master_vt18', '!=', 1); // PWS#13-vt18 vietato ai minori // PWS#15
          $query->orWhere(function($query){
            $query->whereNotIn('ca.master_id', function($query){
              $query->select('mr.master_id')
                ->from('master_relazioni as mr')
                ->where('mr.elemento_id', '=', DB::raw(config('constants.master.generi.film_per_adulti')))
                ->where('mr.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
            });
          });
        }
        $query->whereIn('ca.master_id', function($query) use($releases){
          $query->from('master_relazioni')
          ->selectRaw('master_id')
          ->whereIn('master_relazioni.elemento_id', explode(", ",$releases->genres_ids))
          ->where('tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
        });
      })
      ->addSelect(
        'ca.*',
        'rpf.nome as poster_formato',
        DB::raw(DB::getTablePrefix() . 'release_images.path as path'),
        DB::raw(DB::getTablePrefix() . 'master_images.path as master_path'),
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'generi.generi_name SEPARATOR \', \') as genres_name'),
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'registi.registi_nome_cognome SEPARATOR \', \') as director_name'),
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'countries.name SEPARATOR \', \') as country'),
      )
      ->groupBy('ca.master_id')
      ->take(4)->get()->all(); // PWS#13-related
    }

    if (!$releases) {
      abort(404);
    }
    //return view($this->_config['view']);
    //, compact('releases','images'));
    return view($this->_config['view'], compact('releases', 'images', 'favorite', 'collection', 'related_releases', 'products', 'master_images')); // PWS#13-release

  }

  // START PWS#release-list
  public function list()
  {

    $filters = request()->all();
    $orderby_field = 'created_at';
    $orderby_type = 'asc';
    if(isset($filters['order_by'])){
      switch ($filters['order_by']) {
        case 'alpha_asc':
          $orderby_field = 'original_title';
          $orderby_type = 'asc';
          break;
        case 'alpha_desc':
          $orderby_field = 'original_title';
          $orderby_type = 'desc';
          break;
      }
    }

    $releases = DB::table('releases as ca')
      ->leftJoin('release_images', 'ca.id', '=', 'release_images.release_id')
      ->leftJoin('master_images', 'ca.master_id', '=', 'master_images.master_id')
      ->leftJoin('release_relazioni', 'ca.id', '=', 'release_relazioni.release_id') // PWS#chiusura-lang
      ->leftJoin('master', 'ca.master_id', '=', 'master.master_id')
      ->leftJoin('master_relazioni', 'ca.master_id', '=', 'master_relazioni.master_id')
      ->leftJoin('generi', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'generi.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
      })
      ->leftJoin('registi', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'registi.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.registi')));
      })
      ->leftJoin('language', function ($join) {
        $join->on('release_relazioni.elemento_id', '=', 'language.id');
        $join->on('release_relazioni.tipo_relazione', '=', DB::raw(config('constants.release.relazioni.lingua')));
      })
      ->addSelect(
        'ca.id',
        DB::raw(DB::getTablePrefix() . 'release_images.path as path'),
        DB::raw(DB::getTablePrefix() . 'master_images.path as master_path'),
        'ca.release_year',
        'ca.url_key',
        'ca.language',
        'ca.other_title',
        'ca.original_title',
        'ca.releasetype',
        'ca.release_status',
        'ca.created_at',
        'ca.release_vt18',
        'ca.release_is_visible',
        'ca.country',
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'generi.generi_name SEPARATOR \', \') as genres_name'),
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'registi.registi_nome_cognome SEPARATOR \', \') as director_name'),
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'language.name SEPARATOR \', \') as lang_name'),
      )
      ->where('ca.release_status', DB::raw(1))
      ->where('ca.release_is_visible', DB::raw(1))
      ->where(function($query){

        $filters = request()->all();
        if($filters){
          // FILTRO PER GENERE IN "OR"
          // if(isset($filters['generi']) && count($filters['generi'])){
          //   $query->whereIn('ca.master_id', function($query) use($filters){
          //     $query->from('master_relazioni')
          //     ->selectRaw('master_id')
          //     ->whereIn('elemento_id', array_values($filters['generi']))
          //     ->where('tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
          //   });
          // }

          // FILTRO PER GENERE IN "AND"
          if(isset($filters['generi']) && count($filters['generi'])){
            foreach($filters['generi'] as $key => $genere){
              $query->whereIn('ca.master_id', function($query) use($genere){
                $query->from('master_relazioni')
                ->selectRaw('master_id')
                ->where('elemento_id', '=', $genere)
                ->where('tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
              });
            }
          }

          if(isset($filters['term']) && strlen($filters['term']) > 0){
            $query->where('ca.original_title', 'LIKE', "%" . $filters['term'] . "%");
          }

          if(isset($filters['year']) && strlen($filters['year']) > 0){
            $query->where('ca.release_year', '=', $filters['year']);
          } // PWS#13-filtri

          if(isset($filters['language']) && strlen($filters['language']) > 0){ // PWS#chiusura
            $query->whereIn('ca.id', function($query) use($filters){
              $query->from('release_relazioni')
              ->leftJoin('language', function ($join) use($filters) {
                $join->on('language.id', '=', 'release_relazioni.elemento_id');
                $join->on('release_relazioni.tipo_relazione', '=', DB::raw(config('constants.release.relazioni.lingua')));
              })
              ->selectRaw('release_id')
              ->where('code', '=', DB::raw("'" . $filters['language'] . "'"))
              ->where('tipo_relazione', '=', DB::raw(config('constants.release.relazioni.lingua')));
            });
          }

          if(isset($filters['master']) && strlen($filters['master']) > 0){
            $query->where('ca.master_id', '=', $filters['master']);
          }

          if(isset($filters['tipo']) && is_array($filters['tipo']) && count($filters['tipo']) == 1 && strlen($filters['tipo'][0]) > 0){ // è un parametro post
            if($filters['tipo'][0] === 'poster'){
              $query->where('ca.releasetype', '=', DB::raw(config('constants.release.tipo.poster')));
            } else {
              $query->where('ca.releasetype', '!=', DB::raw(config('constants.release.tipo.poster')));
            }
          } else if(isset($filters['tipo']) && !is_array($filters['tipo']) && strlen($filters['tipo']) > 0){ // è un parametro get
            if($filters['tipo'] === 'poster'){
              $query->where('ca.releasetype', '=', DB::raw(config('constants.release.tipo.poster')));
            } else {
              $query->where('ca.releasetype', '!=', DB::raw(config('constants.release.tipo.poster')));
            }
          }

          /* PWS#chiusura */
          if(isset($filters['catalogo']) && strlen($filters['catalogo']) > 0){
            $catalogo = $filters['catalogo'];
            $query->where('ca.numero_catalogo', '=', DB::raw($filters['catalogo']));
          }
          /* PWS#chiusura */
        }
      })
      ->groupBy('ca.id')
      ->orderBy($orderby_field, $orderby_type)->paginate(isset($filters['per_page']) ? $filters['per_page'] : 15); // PWS#frontend

      $generi = DB::table('generi as g')
        ->where('g.status', 1)
        ->orderBy('g.generi_name', 'asc')->get(); // PWS#230101

      $lingue = DB::table('language as l')
        ->where('l.status', 1)
        ->orderBy('l.name', 'asc')->get(); // PWS#230101

// var_dump($releases);die();
    return view($this->_config['view'], compact('releases', 'generi', 'lingue', 'filters')); // PWS#finale
  }
  // END PWS#release-list

  /**
   * Customer delete a reviews from their account
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $releases = $this->releaseRepository->findOneWhere([
      'id' => $id,
      'customer_id' => auth()->guard('customer')->user()->id,
    ]);

    if (!$releases) {
      abort(404);
    }

    $this->releaseRepository->delete($id);

    session()->flash('success', trans('shop::app.response.delete-success', ['name' => 'Versione']));

    return redirect()->route($this->_config['redirect']);
  }

  /**
   * Customer delete all reviews from their account
   *
   * @return \Illuminate\Http\Response
   */
  public function deleteAll()
  {
    $reviews = auth()->guard('customer')->user()->all_reviews;

    foreach ($reviews as $review) {
      $this->releaseRepository->delete($review->id);
    }

    session()->flash('success', trans('shop::app.release.delete-all'));

    return redirect()->route($this->_config['redirect']);
  }

  //prova ima
  public function getProductImage($item)
  {

    $release = $item->release;

    return $this->getProductBaseImage($release);
  }


  /**
   * This method will first check whether the gallery images are already
   * present or not. If not then it will load from the product.
   *
   * @param  \PSW\Cinema\Film\Contracts\release $release
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
   * @param  \PSW\Cinema\Film\Contracts\Release  $release
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
    if (!$this->isDriverLocal()) {
      return [
        'small_image_url' => Storage::url($path),
        'medium_image_url' => Storage::url($path),
        'large_image_url' => Storage::url($path),
        'original_image_url' => Storage::url($path),
      ];
    }

    return [
      'small_image_url' => url('cache/small/' . $path),
      'medium_image_url' => url('cache/medium/' . $path),
      'large_image_url' => url('cache/large/' . $path),
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
      'small_image_url' => asset('vendor/webkul/ui/assets/images/product/small-product-placeholder.webp'),
      'medium_image_url' => asset('vendor/webkul/ui/assets/images/product/meduim-product-placeholder.webp'),
      'large_image_url' => asset('vendor/webkul/ui/assets/images/product/large-product-placeholder.webp'),
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
  /**
   * Result of search product.
   *
   * @return \Illuminate\View\View|\Illuminate\Http\Response
   */
  public function releaseLinkSearch()
  {
    if (request()->ajax()) {
      $results = [];

      foreach ($this->releaseRepository->searchProductByAttribute(request()->input('query')) as $row) {
        $results[] = [
          'id' => $row->id,
          'original_title' => $row->original_title,
        ];
      }

      return response()->json($results);
    } else {
      return view($this->_config['view']);
    }
  }
  /**
   * Index to handle the view loaded with the search results.
   *
   * @return \Illuminate\View\View
   */
  public function search()
  {
    $results = $this->searchFilmFromTitle(request()->all());

    return view($this->_config['view'])->with('results', $results ? $results : null);
  }

  public function searchFilmFromTitle($params)
  {
    $rtr = '';

    $term = $params['term'] ?? '';
    //$categoryId = $params['category'] ?? '';


    $channel = core()->getRequestedChannelCode();

    $locale = core()->getRequestedLocaleCode();


    // $query = $query->distinct()
    //                ->addSelect('product_flat.*')
    //                ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
    //                ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
    //                ->where('product_flat.status', 1)
    //                ->where('product_flat.visible_individually', 1)
    //                ->where('product_flat.channel', $channel)
    //                ->where('product_flat.locale', $locale)
    //                ->whereNotNull('product_flat.url_key');

    $query = DB::table('release')
      ->addSelect('release.*', DB::raw('' . DB::getTablePrefix() . 'release_images.path as image'))
      // ->addSelect('releases.*')
      ->leftJoin('release_images', 'release.release_id', '=', 'release_images.release_id')
        //->where('releases.release_status', 1)
        // ->where('product_flat.channel', $channel)
        // ->where('product_flat.locale', $locale)
        // ->whereNotNull('releases.url_key')
        //->addFilter('image', 'release_images.path')
      ->whereNotNull('release.url_key');

    if ($term) {
      $query->where('release.release_maintitle', 'like', '%' . urldecode($term) . '%')->orWhere('release.release_othertitle', 'like', '%' . urldecode($term) . '%');

      // $query->where('releases.original_title', 'like', '%'.urldecode($term).'%');
    }
    $results['release'] = $query->groupBy('release.release_id')->paginate(isset($params['limit']) ? $params['limit'] : 9);

    if (count($results['release']) > 0) {
      foreach ($results['release'] as $mast) {
        $query = DB::table('releases')
          ->addSelect('releases.*', DB::raw('' . DB::getTablePrefix() . 'release_images.path as image'))
          ->leftJoin('release_images', 'releases.id', '=', 'release_images.release_id')
            // ->addSelect('releases.*')
            // ->leftJoin('releases', 'release.release_id', '=', 'releases.release_id')
          ->where('releases.release_id', $mast->release_id);
        // ->where('product_flat.channel', $channel)
        // ->where('product_flat.locale', $locale)
        // ->whereNotNull('releases.url_key')
        //->addFilter('image', 'release_images.path');
        // ->whereNotNull('releases.url_key');

        if ($term) {
          $query->where('releases.original_title', 'like', '%' . urldecode($term) . '%');

          // $query->where('releases.original_title', 'like', '%'.urldecode($term).'%');
        }
        $results['release'] = $query->paginate(isset($params['limit']) ? $params['limit'] : 9);

      }

    } else {
      $results['release'] = '';
    }
    // if (isset($params['sort'])) {
    //     $attribute = $this->attributeRepository->findOneByField('code', $params['sort']);

    //     if ($params['sort'] == 'price') {
    //         if ($attribute->code == 'price') {
    //             $query->orderBy('min_price', $params['order']);
    //         } else {
    //             $query->orderBy($attribute->code, $params['order']);
    //         }
    //     } else {
    //         $query->orderBy($params['sort'] == 'created_at' ? 'product_flat.created_at' : $attribute->code, $params['order']);
    //     }
    // }


    // $query = $query->where(function($query1) use($query) {
    //     $aliases = [
    //         'products' => 'filter_',
    //         'variants' => 'variant_filter_',
    //     ];

    //     foreach($aliases as $table => $alias) {
    //         $query1 = $query1->orWhere(function($query2) use ($query, $table, $alias) {

    //             foreach ($this->attributeRepository->getProductDefaultAttributes(array_keys(request()->input())) as $code => $attribute) {
    //                 $aliasTemp = $alias . $attribute->code;

    //                 $query = $query->leftJoin('product_attribute_values as ' . $aliasTemp, $table . '.id', '=', $aliasTemp . '.product_id');

    //                 $column = ProductAttributeValue::$attributeTypeFields[$attribute->type];

    //                 $temp = explode(',', request()->get($attribute->code));

    //                 if ($attribute->type != 'price') {
    //                     $query2 = $query2->where($aliasTemp . '.attribute_id', $attribute->id);

    //                     $query2 = $query2->where(function($query3) use($aliasTemp, $column, $temp) {
    //                         foreach($temp as $code => $filterValue) {
    //                             if (! is_numeric($filterValue))
    //                                 continue;

    //                             $columns = $aliasTemp . '.' . $column;
    //                             $query3 = $query3->orwhereRaw("find_in_set($filterValue, $columns)");
    //                         }
    //                     });
    //                 } else {
    //                     $query2->where('product_flat.min_price', '>=', core()->convertToBasePrice(current($temp)))
    //                            ->where('product_flat.min_price', '<=', core()->convertToBasePrice(end($temp)));
    //                 }
    //             }
    //         });
    //     }
    // });

    // $results['release'] = $query->groupBy('release.release_id')->paginate(isset($params['limit']) ? $params['limit'] : 9);
    // echo count($results);


    // if (count($results)>0){
    //     $obj_merged = $user_owned_orgs->merge($results);
    //     foreach($results as $rt){

    //         $query = DB::table('releases')->distinct()
    //             ->addSelect('releases.*')
    //             // ->addSelect('releases.*')
    //             // ->leftJoin('releases', 'release.release_id', '=', 'releases.release_id')
    //             ->where('releases.release_id', $rt->release_id)
    //             // ->where('product_flat.channel', $channel)
    //             // ->where('product_flat.locale', $locale)
    //             // ->whereNotNull('releases.url_key')
    //             ->whereNotNull('releases.url_key');

    //             if ($term){
    //                 $query->where('releases.original_title', 'like', '%'.urldecode($term).'%')->orWhere('releases.other_title', 'like', '%'.urldecode($term).'%');
    //                 // $query->where('releases.original_title', 'like', '%'.urldecode($term).'%');
    //             }
    //             $results =$query->groupBy('releases.release_id')->paginate(isset($params['limit']) ? $params['limit'] : 9);

    //             echo '<pre>';print_r( $results);
    //             //echo '<pre>';print_r($rt->release_id);
    //             if (count($results)){
    //               //  array_push($rtr,$results);
    //               $obj_merged = $user_owned_orgs->merge($results);
    //             }
    //     }
    // }

    //$results = ->paginate(isset($params['limit']) ? $params['limit'] : 9);
    // dd($results);
    //echo '<pre>';print_r($results);
    return $results;

  }
}
