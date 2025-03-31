<?php

namespace PSW\Cinema\Shop\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use PSW\Cinema\Film\Repositories\MastersRepository;
use Illuminate\Support\Facades\Auth; // PWS#13-release

class MasterController extends Controller
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
   * Display master of particular product.
   *
   * @param  string  $slug
   * @return \Illuminate\View\View
   */
  public function show($id, $slug)
  {
    //echo 'IDDD '.$id.' -- '.$slug;
    $master = DB::table('master AS ca')
      ->leftJoin('master_relazioni', 'ca.master_id', '=', 'master_relazioni.master_id')
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
      ->leftJoin('language', function ($join) { // PWS#10-lang
        $join->on('master_relazioni.elemento_id', '=', 'language.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.lingua')));
      }) // PWS#10-lang
      ->addSelect(
        'ca.url_key',
        'ca.master_trama', // PWS#02-23-x
        'ca.meta_title',
        'meta_description',
        'meta_keywords',
        'master_description',
        DB::raw('GROUP_CONCAT(DISTINCT
          CONCAT(
            ' . DB::getTablePrefix() . 'attori.id, "_*_",
              CASE
                WHEN ' . DB::getTablePrefix() . 'attori.attori_alias = "" OR ' . DB::getTablePrefix() . 'attori.attori_alias IS NULL THEN ' . DB::getTablePrefix() . 'attori.attori_nome_cognome
                ELSE ' . DB::getTablePrefix() . 'attori.attori_alias
              END
            )
              SEPARATOR \', \'
              ) as attori_name'), // PWS#10 -> mostra alias quando disponibile, altrimenti nome-cognome
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'compositori.compo_nome_cognome SEPARATOR \', \') as compositori_name'),
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'casaproduzione.casa_nome SEPARATOR \', \') as casaproduzione_nome'),
        'ca.master_id as master_id',
        'ca.master_maintitle',
        'ca.master_othertitle',
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'countries.name SEPARATOR \', \') as country'),
        'ca.master_genres',
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'generi.generi_name SEPARATOR \', \') as genres_name'),
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'generi.id SEPARATOR \', \') as genres_ids'),
        'ca.master_subgenres',
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'sottogeneri.subge_name SEPARATOR \', \') as subgenres_name'),
        'ca.master_director',
        DB::raw('GROUP_CONCAT(DISTINCT CONCAT(' . DB::getTablePrefix() . 'registi.id, "_*_",' . DB::getTablePrefix() . 'registi.registi_nome_cognome) SEPARATOR \', \') as director_name'), // PWS#chiusura
        'ca.master_year',
        'ca.master_actors',
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'sceneggiatori.scene_nome_cognome SEPARATOR \', \') as sceneggiatori_name'),
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'language.name SEPARATOR \', \') as lingua'), // PWS#10-lang
        'ca.master_musiccomposers',
        'ca.master_language',
        DB::raw('' . DB::getTablePrefix() . 'language.name as language_name'),
        'ca.master_type',
        'ca.master_vt18',
        'ca.master_bn', // PWS#13-bn
        'ca.master_is_visible',
        'ca.master_studios'
      )
      ->where('ca.url_key', '=', $slug)
      ->where('ca.master_id', '=', $id)
      ->first(); // PWS#7-2
    //echo '<pre>';print_r($master); die();
    $images = DB::table('master_images')->where('master_id', '=', $id)->get();
    $relatedmaster = DB::table('master as ca')
      ->leftJoin('master_images', 'ca.master_id', '=', 'master_images.master_id')
      ->leftJoin('master_relazioni', 'ca.master_id', '=', 'master_relazioni.master_id')
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
        $query->where('master_images.position', 0)
        ->orWhereNull('master_images.id');
      })
      ->where('ca.master_id', "!=", $id)
      ->where('ca.master_is_visible', '=', DB::raw(1))
      ->where(function($query) use ($master){
        $user = Auth::user();
        if(!Auth::check() || (Auth::check() && strtotime('18 years ago') < strtotime($user->date_of_birth) && $user->date_of_birth != '0000-00-00' && $user->date_of_birth != false) || (Auth::check() && ($user->date_of_birth == '0000-00-00' || $user->date_of_birth == false))){
          $query->where('ca.master_vt18', '!=', 1); // PWS#13-vt18 vietato ai minori // PWS#15
          $query->orWhere(function($query){
            $query->whereNotIn('ca.master_id', function($query){
              $query->select('mr.master_id')
                ->from('master_relazioni as mr')
                ->where('mr.elemento_id', '=', DB::raw(config('constants.master.generi.film_per_adulti')))
                ->where('mr.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
            });
          });
        }
      })
      ->where(function($query) use ($master){
        $query->whereIn('ca.master_id', function($query) use($master){
          $query->from('master_relazioni')
          ->selectRaw('master_id')
          ->whereIn('master_relazioni.elemento_id', explode(", ",$master->genres_ids))
          ->where('tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
        });
      })
      ->inRandomOrder()
      ->addSelect(
        'ca.*',
        DB::raw(DB::getTablePrefix() . 'master_images.path as path'),
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'generi.generi_name SEPARATOR \', \') as genres_name'),
        DB::raw('GROUP_CONCAT(DISTINCT CONCAT(' . DB::getTablePrefix() . 'registi.id, "_*_",' . DB::getTablePrefix() . 'registi.registi_nome_cognome) SEPARATOR \', \') as director_name'), // PWS#chiusura
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'countries.name SEPARATOR \', \') as country'),
      )
      ->groupBy('ca.master_id')
      ->take(4)->get()->all(); // PWS#13-related

      $user = Auth::user();

      if(($master->master_vt18 && in_array(config('constants.master.generi.film_per_adulti'), explode(',',$master->genres_ids))) && (!Auth::check() || (Auth::check() && strtotime('18 years ago') < strtotime($user->date_of_birth) && $user->date_of_birth != '0000-00-00' && $user->date_of_birth != false) || (Auth::check() && ($user->date_of_birth == '0000-00-00' || $user->date_of_birth == false)))){
        return redirect()->route('shop.cinema.vt18');
      } // PWS#13-vt18 // PWS#15

      $favorite = false;
      if(Auth::check()){
        $master_user_favorite = DB::table('master_user_favorite')
          ->where('master_id', '=', DB::raw($id))
          ->where('user_id', '=', DB::raw(Auth::id()))
          ->get()->first();

        if($master_user_favorite) $favorite = true;
      } // PWS#13-release

      $related_releases = DB::table('releases as ca')
      ->leftJoin('language', 'ca.language', '=', 'language.id')
      ->leftJoin('releaseType as rt', 'ca.releasetype', '=', 'rt.id')
      ->leftJoin('release_images', 'ca.id', '=', 'release_images.release_id')
      ->leftJoin('countries', 'ca.country', '=', 'countries.code')
      ->where('ca.master_id', '=', DB::raw($id))
      ->where('ca.release_status', '=', DB::raw(1))
      ->where('ca.release_is_visible', '=', DB::raw(1))
      ->whereNotNull('ca.url_key')
      ->addSelect(
        'ca.*',
        'rt.name as release_tipo',
        'language.name as lingua',
        'countries.name as paese', // PWS#18-2
        'release_images.path AS image_path',
        'release_images.type AS image_type',
        DB::raw("
            (
              SELECT COUNT(*)
              FROM " . DB::getTablePrefix() . "product_attribute_values AS pav
              LEFT JOIN " . DB::getTablePrefix() . "attributes AS pa ON pa.id = pav.attribute_id
              WHERE pa.code = 'release_id' AND pav.integer_value = " . DB::getTablePrefix() . "ca.id
            ) AS products_counter"
          ), // PWS#prod
      )
      ->get()->all(); // PWS#related-release-2

      $releases_favorite_array = array();
      if(Auth::check()){
        $releases_favorite_array = DB::table('release_user_favorite')
          ->where('user_id', '=', DB::raw(Auth::id()))
          ->pluck('release_id')
          ->toArray();
      }

      $releases_collection_array = array();
      if(Auth::check()){
        $releases_collection_array = DB::table('release_user')
          ->where('user_id', '=', DB::raw(Auth::id()))
          ->pluck('release_id')
          ->toArray();
      }

    // foreach ($images as $key => $img) {
      //           echo '<pre>';print_r($key); echo ' ---- '; print_r($img);
      //    die();
      // $id =parent::getProductImage($img);
    // }


    if (!$master || $master->master_is_visible != 1) {
      abort(404);
    }
    return view($this->_config['view'], compact('master', 'images', 'relatedmaster', 'favorite', 'related_releases', 'releases_favorite_array', 'releases_collection_array'));

  }

  // START PWS#master-list
  public function list()
  {

    $filters = request()->all();
    $orderby_field = 'created_at';
    $orderby_type = 'desc'; // PWS#chiusura
    if(isset($filters['order_by'])){
      switch ($filters['order_by']) {
        case 'alpha_asc':
          $orderby_field = 'master_maintitle';
          $orderby_type = 'asc';
          break;
        case 'alpha_desc':
          $orderby_field = 'master_maintitle';
          $orderby_type = 'desc';
          break;
      }
    }

    if(isset($filters['master_type']) && $filters['master_type'] == 'poster'){ // PWS#chiusura
      return redirect()->route('shop.cinema.releases.list', ['tipo' => 'poster']);
    }

    $masters = DB::table('master as ca')
      ->leftJoin('master_images', 'ca.master_id', '=', 'master_images.master_id')
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
        $join->on('master_relazioni.elemento_id', '=', 'language.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.lingua')));
      })
      ->leftJoin('countries', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'countries.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.countries')));
      }) // PWS#13-paese
      ->addSelect(
        'ca.master_id',
        DB::raw(DB::getTablePrefix() . 'master_images.path as path'),
        'ca.master_year',
        'ca.url_key',
        'ca.master_language',
        'ca.master_othertitle',
        'ca.master_maintitle',
        'ca.master_type',
        'ca.master_status',
        'ca.created_at',
        'ca.master_vt18',
        'ca.master_is_visible',
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'generi.generi_name SEPARATOR \', \') as genres_name'),
        DB::raw('GROUP_CONCAT(DISTINCT CONCAT(' . DB::getTablePrefix() . 'registi.id, "_*_",' . DB::getTablePrefix() . 'registi.registi_nome_cognome) SEPARATOR \', \') as director_name'), // PWS#chiusura
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'language.name SEPARATOR \', \') as lang_name'),
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'countries.name SEPARATOR \', \') as country'),
      )
      ->where('ca.master_is_visible', '=', DB::raw(1))
      ->where(function($query){
        $user = Auth::user();
        if(!Auth::check() || (Auth::check() && strtotime('18 years ago') < strtotime($user->date_of_birth) && $user->date_of_birth != '0000-00-00' && $user->date_of_birth != false) || (Auth::check() && ($user->date_of_birth == '0000-00-00' || $user->date_of_birth == false))){
          $query->where('ca.master_vt18', '!=', 1); // PWS#13-vt18 vietato ai minori // PWS#15
          $query->orWhere(function($query){
            $query->whereNotIn('ca.master_id', function($query){
              $query->select('mr.master_id')
                ->from('master_relazioni as mr')
                ->where('mr.elemento_id', '=', DB::raw(config('constants.master.generi.film_per_adulti')))
                ->where('mr.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
            });
          });
        } // PWS#15-2
      })
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

          /* PWS#chiusura */
          if(isset($filters['master_type']) && strlen($filters['master_type']) > 0){
            switch ($filters['master_type']) {
              case 'serie':
                $type_filter = array('movie-episode-TV');
                break;
              case 'movie-episode':
                $type_filter = array('movie-episode');
                break;
              default:
                $type_filter = array('movie-episode', $filters['master_type']);
                break;
            }
            $query->whereIn('ca.master_type', $type_filter);
          }

          if(isset($filters['regista']) && strlen($filters['regista']) > 0){
            $regista = $filters['regista'];
            $query->whereIn('ca.master_id', function($query) use($regista){
              $query->from('master_relazioni')
              ->selectRaw('master_id')
              ->where('elemento_id', '=', $regista)
              ->where('tipo_relazione', '=', DB::raw(config('constants.master.relazioni.registi')));
            });
          }

          if(isset($filters['attore']) && strlen($filters['attore']) > 0){
            $attore = $filters['attore'];
            $query->whereIn('ca.master_id', function($query) use($attore){
              $query->from('master_relazioni')
              ->selectRaw('master_id')
              ->where('elemento_id', '=', $attore)
              ->where('tipo_relazione', '=', DB::raw(config('constants.master.relazioni.attori')));
            });
          }

          if(isset($filters['catalogo']) && strlen($filters['catalogo']) > 0){
            $catalogo = $filters['catalogo'];
            $query->whereIn('ca.master_id', function($query) use($catalogo){
              $query->from('releases')
              ->selectRaw('master_id')
              ->where('numero_catalogo', '=', $catalogo)
              ->where('release_is_visible', '=', DB::raw(1))
              ->where('release_status', '=', DB::raw(1));
            });
          }
          /* PWS#chiusura */

          if(isset($filters['term']) && strlen($filters['term']) > 0){
            $query->where('ca.master_maintitle', 'LIKE', "%" . $filters['term'] . "%");
          }

          if(isset($filters['country']) && strlen($filters['country']) > 0){
            $query->whereIn('ca.master_id', function($query) use($filters){
              $query->from('master_relazioni')
              ->leftJoin('countries', function ($join) use($filters) {
                $join->on('countries.id', '=', 'master_relazioni.elemento_id');
                $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.countries')));
              })
              ->selectRaw('master_id')
              ->where('code', '=', $filters['country'])
              ->where('tipo_relazione', '=', DB::raw(config('constants.master.relazioni.countries')));
            });
          }

          if(isset($filters['year']) && strlen($filters['year']) > 0){
            $query->where('ca.master_year', '=', $filters['year']);
          } // PWS#13-filtri

          if(isset($filters['language']) && strlen($filters['language']) > 0){
            $query->where('language.code', '=', $filters['language']);
          }
        }
      })
      ->groupBy('ca.master_id')
      ->orderBy($orderby_field, $orderby_type)->paginate(isset($filters['per_page']) ? $filters['per_page'] : 15); // PWS#frontend | PWS#10-lang

      $generi = DB::table('generi as g')
        ->where('g.status', 1)
        ->orderBy('g.generi_name', 'asc')->get(); // PWS#230101

      $countries = DB::table('countries as c')
        ->orderBy('c.name', 'asc')->get(); // PWS#video-poster

      $lingue = DB::table('language as l')
        ->where('l.status', 1)
        ->orderBy('l.name', 'asc')->get(); // PWS#230101

      /* PWS#chiusura */
      $attori = DB::table('attori as a')
        ->where('a.status', 1)
        ->orderBy('a.attori_nome_cognome', 'asc')->get();

      $registi = DB::table('registi as r')
        ->where('r.status', 1)
        ->orderBy('r.registi_nome_cognome', 'asc')->get();

    return view($this->_config['view'], compact('masters', 'generi', 'lingue', 'countries', 'filters', 'attori', 'registi')); // PWS#finale PWS#chiusura
  }
  // END PWS#master-list

  // START PWS#13-lightbox
  public function getImgsLightbox($master_id){
    $imgs = DB::table('master_images')->where('master_id', '=', $master_id)->get();
    $arrayimg = [];
    foreach($imgs as $img){
      array_push($arrayimg,
        (object) [
          'thumb' => url('cache/small/' . $img->path),
          'src' => url('cache/large/' . $img->path),
          'caption' => '',
        ]
      );
    }
    return $arrayimg;
  }
  // END PWS#13-lightbox

  /**
   * Customer delete a reviews from their account
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $masters = $this->masterRepository->findOneWhere([
      'id' => $id,
      'customer_id' => auth()->guard('customer')->user()->id,
    ]);

    if (!$masters) {
      abort(404);
    }

    $this->masterRepository->delete($id);

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
      $this->masterRepository->delete($review->id);
    }

    session()->flash('success', trans('shop::app.master.delete-all'));

    return redirect()->route($this->_config['redirect']);
  }


  //prova ima
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
  public function masterLinkSearch()
  {
    if (request()->ajax()) {
      $results = [];

      foreach ($this->mastersRepository->searchProductByAttribute(request()->input('query')) as $row) {
        $results[] = [
          'id' => $row->master_id,
          'master_maintitle' => $row->master_maintitle,
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

    $query = DB::table('master')
      ->addSelect('master.*', DB::raw('' . DB::getTablePrefix() . 'master_images.path as image'))
      // ->addSelect('masters.*')
      ->leftJoin('master_images', 'master.master_id', '=', 'master_images.master_id')
        //->where('masters.master_status', 1)
        // ->where('product_flat.channel', $channel)
        // ->where('product_flat.locale', $locale)
        // ->whereNotNull('masters.url_key')
        //->addFilter('image', 'master_images.path')
      ->whereNotNull('master.url_key');

    if ($term) {
      $query->where('master.master_maintitle', 'like', '%' . urldecode($term) . '%')->orWhere('master.master_othertitle', 'like', '%' . urldecode($term) . '%');

      // $query->where('masters.original_title', 'like', '%'.urldecode($term).'%');
    }
    $results['master'] = $query->groupBy('master.master_id')->paginate(isset($params['limit']) ? $params['limit'] : 9);

    if (count($results['master']) > 0) {
      foreach ($results['master'] as $mast) {
        $query = DB::table('master')
          ->addSelect('master.*', DB::raw('' . DB::getTablePrefix() . 'master_images.path as image'))
          ->leftJoin('master_images', 'master.master_id', '=', 'master_images.master_id')
            // ->addSelect('masters.*')
            // ->leftJoin('masters', 'master.master_id', '=', 'masters.master_id')
          ->where('master.master_id', $mast->master_id);
        // ->where('product_flat.channel', $channel)
        // ->where('product_flat.locale', $locale)
        // ->whereNotNull('masters.url_key')
        //->addFilter('image', 'master_images.path');
        // ->whereNotNull('masters.url_key');

        if ($term) {
          $query->where('master.master_maintitle', 'like', '%' . urldecode($term) . '%');

          // $query->where('masters.original_title', 'like', '%'.urldecode($term).'%');
        }
        $results['master'] = $query->paginate(isset($params['limit']) ? $params['limit'] : 9);

      }

    } else {
      $results['master'] = $query->paginate(isset($params['limit']) ? $params['limit'] : 9); // PWS#7-2
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

    // $results['master'] = $query->groupBy('master.master_id')->paginate(isset($params['limit']) ? $params['limit'] : 9);
    // echo count($results);


    // if (count($results)>0){
    //     $obj_merged = $user_owned_orgs->merge($results);
    //     foreach($results as $rt){

    //         $query = DB::table('masters')->distinct()
    //             ->addSelect('masters.*')
    //             // ->addSelect('masters.*')
    //             // ->leftJoin('masters', 'master.master_id', '=', 'masters.master_id')
    //             ->where('masters.master_id', $rt->master_id)
    //             // ->where('product_flat.channel', $channel)
    //             // ->where('product_flat.locale', $locale)
    //             // ->whereNotNull('masters.url_key')
    //             ->whereNotNull('masters.url_key');

    //             if ($term){
    //                 $query->where('masters.original_title', 'like', '%'.urldecode($term).'%')->orWhere('masters.other_title', 'like', '%'.urldecode($term).'%');
    //                 // $query->where('masters.original_title', 'like', '%'.urldecode($term).'%');
    //             }
    //             $results =$query->groupBy('masters.master_id')->paginate(isset($params['limit']) ? $params['limit'] : 9);

    //             echo '<pre>';print_r( $results);
    //             //echo '<pre>';print_r($rt->master_id);
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
