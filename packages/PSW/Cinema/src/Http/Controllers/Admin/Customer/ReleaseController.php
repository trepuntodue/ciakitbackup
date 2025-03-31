<?php

namespace PSW\Cinema\Http\Controllers\Admin\Customer;

use PSW\Cinema\DataGrids\ReleaseDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Customer\Repositories\CustomerRepository;
use PSW\Cinema\Customer\Repositories\CustomerReleaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // PWS#230101
use Webkul\Customer\Models\Customer;
use PSW\Cinema\Film\Repositories\ReleaseRepository;


class ReleaseController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;
    /**
     * Current customer.
     *
     * @var \Webkul\Customer\Models\Customer
     */
    protected $customer;
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param  \PSW\Cinema\Customer\Repositories\CustomerReleaseRepository  $customerReleaseRepository
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CustomerReleaseRepository $customerReleaseRepository,
        // protected ReleaseRepository $releaseRepository
    )
    {
        $this->_config = request('_config');
        $this->customer = auth()->guard('customer')->user();
    }

    /**
     * Fetch address by customer id.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $customer_id = $this->customer->id;
        // echo '<pre>';print_r($releases);
        // die("casdas");
       $releases = DB::table('releases')
       ->leftJoin('master', 'releases.master_id', '=', 'master.master_id')
       ->leftJoin('master_relazioni', 'releases.master_id', '=', 'master_relazioni.master_id')
       ->leftJoin('release_relazioni', 'releases.id', '=', 'release_relazioni.release_id')
       ->leftJoin('releaseType as rt', function ($join) {
         $join->on('releases.releasetype', '=', 'rt.id');
       }) // PWS#02-23
       ->leftJoin('release_user', function ($join) use($customer_id) {
         $join->on('releases.id', '=', 'release_user.release_id');
       })
       ->leftJoin('casaproduzione', function ($join) {
         $join->on('master_relazioni.elemento_id', '=', 'casaproduzione.id');
         $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.casaproduzione')));
       })
       ->leftJoin('release_images', 'releases.id', '=', 'release_images.release_id')
       ->leftJoin('master_images', 'releases.master_id', '=', 'master_images.master_id')
       ->leftJoin('language', function ($join) { // PWS#chiusura-lang
         $join->on('release_relazioni.elemento_id', '=', 'language.id');
         $join->on('release_relazioni.tipo_relazione', '=', DB::raw(config('constants.release.relazioni.lingua')));
       })
       ->where('release_user.user_id', '=', DB::raw($customer_id))
       ->select(
         DB::raw(
           DB::getTablePrefix() . 'releases.*, ' .
           DB::getTablePrefix() . 'rt.name as releasetype, ' . // PWS#02-23
           DB::getTablePrefix() . 'releases.id AS release_id',
         ),
         'master_images.path as master_path', // PWS#chiusura
         'release_images.path as path',
         'master.master_maintitle',
         'rt.name as release_tipo',
         DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'casaproduzione.casa_nome SEPARATOR \', \') as casaproduzione_nome'),
         DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'language.name SEPARATOR \', \') as lingua'), // PWS#chiusura-lang
        )
        ->groupBy('releases.id')
       ->orderBy('releases.id', 'desc')
       ->get(); // PWS#230101 // PWS#13-release

        // return view($this->_config['view'], ['releases' => $releases]);
        return view($this->_config['view'])->with('releases', $releases);
    }

    public function favorites()
    {
      $customer_id = $this->customer->id;
      // echo '<pre>';print_r($releases);
      // die("casdas");
      $releases = DB::table('releases')
      ->leftJoin('master', 'releases.master_id', '=', 'master.master_id')
      ->leftJoin('master_relazioni', 'releases.master_id', '=', 'master_relazioni.master_id')
      ->leftJoin('release_relazioni', 'releases.id', '=', 'release_relazioni.release_id')
      ->leftJoin('releaseType as rt', function ($join) {
        $join->on('releases.releasetype', '=', 'rt.id');
      }) // PWS#02-23
      ->leftJoin('release_user_favorite', function ($join) use($customer_id) {
        $join->on('releases.id', '=', 'release_user_favorite.release_id');
      })
      ->leftJoin('casaproduzione', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'casaproduzione.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.casaproduzione')));
      })
      ->leftJoin('release_images', 'releases.id', '=', 'release_images.release_id')
      ->leftJoin('master_images', 'releases.master_id', '=', 'master_images.master_id')
      ->leftJoin('language', function ($join) { // PWS#chiusura-lang
        $join->on('release_relazioni.elemento_id', '=', 'language.id');
        $join->on('release_relazioni.tipo_relazione', '=', DB::raw(config('constants.release.relazioni.lingua')));
      })
      ->where('release_user_favorite.user_id', '=', DB::raw($customer_id))
      ->select(
        DB::raw(
          DB::getTablePrefix() . 'releases.*, ' .
          DB::getTablePrefix() . 'rt.name as releasetype, ' . // PWS#02-23
          DB::getTablePrefix() . 'releases.id AS release_id',
        ),
        'master_images.path as master_path', // PWS#chiusura
        'release_images.path as path',
        'master.master_maintitle',
        'rt.name as release_tipo',
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'casaproduzione.casa_nome SEPARATOR \', \') as casaproduzione_nome'),
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'language.name SEPARATOR \', \') as lingua'), // PWS#chiusura-lang
      )
      ->groupBy('releases.id')
      ->orderBy('releases.id', 'desc')
      ->get(); // PWS#230101 // PWS#13-release


        // return view($this->_config['view'], ['releases' => $releases]);
        return view($this->_config['view'])->with('releases', $releases);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function create($master_id = false)
    {
        // $customer = $this->customerRepository->find($id); // PWS#230101

        $filters = request()->all(); // PWS#video-poster

        $action = isset($filters['action']) ? $filter['actions'] : false;

        $customer = Auth::user();

        $customer_id = $this->customer->id;

        $language = DB::table('language')->orderBy('name', 'asc')->get();
        $releasetype = DB::table('releaseType')->orderBy('name', 'asc')->get();
        $masters = DB::table('master')->orderBy('master_maintitle', 'asc')->get(); // PWS#8-link-master
        $selected_master = false; // START PWS#230101
        if(is_numeric($master_id)){
          $selected_master = DB::table('master AS ca')
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
              'ca.meta_title',
              'meta_description',
              'meta_keywords',
              'master_description',
              DB::raw('GROUP_CONCAT(DISTINCT
                    CASE
                      WHEN ' . DB::getTablePrefix() . 'attori.attori_alias = "" OR ' . DB::getTablePrefix() . 'attori.attori_alias IS NULL THEN ' . DB::getTablePrefix() . 'attori.attori_nome_cognome
                      ELSE ' . DB::getTablePrefix() . 'attori.attori_alias
                    END
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
              'ca.master_subgenres',
              DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'sottogeneri.subge_name SEPARATOR \', \') as subgenres_name'),
              'ca.master_director',
              DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'registi.registi_nome_cognome SEPARATOR \', \') as director_name'),
              'ca.master_year',
              'ca.master_actors',
              DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'sceneggiatori.scene_nome_cognome SEPARATOR \', \') as sceneggiatori_name'),
              DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'language.name SEPARATOR \', \') as lingua'), // PWS#10-lang
              'ca.master_musiccomposers',
              'ca.master_language',
              DB::raw('' . DB::getTablePrefix() . 'language.name as language_name'),
              'ca.master_type',
              'ca.master_vt18',
              'ca.master_is_visible',
              'ca.master_studios'
            )
            ->where('ca.master_id', '=', $master_id)
            ->first();
        } // END PWS#230101

        // START PWS#02-23
        $release_formato = DB::table('release_formato')->where('status', 1)->orderBy('nome', 'asc')->get();
        $release_aspect_ratio = DB::table('release_aspect_ratio')->where('status', 1)->orderBy('nome', 'asc')->get();
        $release_camera_format = DB::table('release_camera_format')->where('status', 1)->orderBy('nome', 'asc')->get();
        $release_region_code = DB::table('release_region_code')->where('status', 1)->orderBy('nome', 'asc')->get();
        $release_tipologia = DB::table('release_tipologia')->where('status', 1)->orderBy('nome', 'asc')->get();
        $release_canali_audio = DB::table('release_canali_audio')->where('status', 1)->orderBy('nome', 'asc')->get();
        $release_poster_tipo = DB::table('release_poster_tipo')->where('status', 1)->orderBy('nome', 'asc')->get(); // PWS#video-poster
        $release_poster_formato = DB::table('release_poster_formato')->where('status', 1)->orderBy('nome', 'asc')->get();
        $release_poster_misure = DB::table('release_poster_misure')->where('status', 1)->orderBy('nome', 'asc')->get();
        // END PWS#02-23

        return view($this->_config['view'], array_merge(compact('language','releasetype','customer','masters','customer_id','selected_master','release_formato','release_aspect_ratio','release_camera_format','release_region_code','release_tipologia','release_canali_audio','release_poster_tipo','release_poster_formato','release_poster_misure', 'action'), [
            'defaultCountry' =>'',
            'defaultLanguage' =>'',
            'defaultType' =>'',
        ]));
       // return view($this->_config['view'], compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
      // request()->merge([
      //     'address1' => implode(PHP_EOL, array_filter(request()->input('address1'))),
      // ]);

      $data = collect(request()->input())->except('_token')->toArray();

      // PWS#video-poster

      if(isset($data['action']) && $data['action'] == 'search'){
        if(isset($data['tipo']) && ((isset($data['numero_catalogo']) && strlen($data['numero_catalogo']) >= 3) || (isset($data['titolo']) && strlen($data['titolo']) >= 3))){
          if($data['tipo'] == 'video'){
            $field = 'numero_catalogo';
            $value = $data['numero_catalogo'];
          } else if($data['tipo'] == 'poster'){
            $field = 'original_title';
            $value = $data['titolo'];
          } else{
            return response()->json(['success' => false]);

          }
          $releases = view('shop::customers.account.release.release-box', ['releases' => $this->searchBy($field, $value)])->render();
          return response()->json([
            'success' => true,
            'releases' => $releases,
          ]);
        } else{
          return response()->json(['success' => false]);
        }
      } else if(isset($data['action']) && $data['action'] == 'create'){

        $data['release_is_visible'] = 1; // in seguito a cambio logica delle release, le release sono sempre visibili (una volta approvate dall'admin)
        // $data['releasetype'] = ''; // PWS#02-23
        // echo '<pre>';
        // print_r($data);
        // die();
        $this->validate(request(), [
            'master_id'        => 'required',
            'original_title'        => 'required',
            // 'other_title'            => '',
            'country'                => 'required',
            'release_year'           => ['required','size:4'],
            'release_distribution'   => 'required',
            'releasetype'           => 'required',
            'language'       => 'required',
        ]); // PWS#8-link-master

        foreach ($data as $key => $value) { // PWS#chiusura
          if(!is_array($value) && strlen($value) == 0){
            $data[$key] = NULL;
          }
        }

        if ($release = $this->customerReleaseRepository->create($data)) {
            session()->flash('success', trans('admin::app.customers.releases.success-create'));

            // return redirect()->route('admin.customer.edit', ['id' => $data['customer_id']]); // PWS#8-link-master
            return redirect()->route('customer.release.index', ['id' => $release->customer_id]);
        } else {
            session()->flash('success', trans('admin::app.customers.releases.error-create'));

            return redirect()->back();
        }
      }


    }

    public function searchBy($field, $value){
      $customer_id = $this->customer->id;
      $releases = DB::table('releases as ca')
      ->leftJoin('release_images', 'ca.id', '=', 'release_images.release_id')
      ->leftJoin('language', 'ca.language', '=', 'language.id')
      ->leftJoin('master', 'ca.master_id', '=', 'master.master_id')
      ->leftJoin('master_images', 'ca.master_id', '=', 'master_images.master_id')
      ->leftJoin('master_relazioni', 'ca.master_id', '=', 'master_relazioni.master_id')
      ->leftJoin('release_user AS ru', function ($join) use ($customer_id){
        $join->on('ca.id', '=', 'ru.release_id');
        $join->on('ru.user_id', '=', DB::raw($customer_id));
      })
      ->leftJoin('countries', 'ca.country', '=', 'countries.code')
      ->leftJoin('releaseType as rt', 'ca.releasetype', '=', 'rt.id')
      ->leftJoin('generi', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'generi.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
      })
      ->leftJoin('casaproduzione', function ($join) {
        $join->on('master_relazioni.elemento_id', '=', 'casaproduzione.id');
        $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.casaproduzione')));
      })
      ->addSelect(
        'ca.*',
        'master.master_maintitle',
        'rt.name as release_tipo',
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'casaproduzione.casa_nome SEPARATOR \', \') as casaproduzione_nome'),
        'ru.id AS collection_id',
        'countries.name as paese', // PWS#18-2
        'master_images.path as master_path', // PWS#mmm
        'release_images.path as path',
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'generi.generi_name SEPARATOR \', \') as genres_name'),
        DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'language.name SEPARATOR \', \') as lingua'),
      )
      // ->where('ca.' . $field, '=', $value)
      ->where(function($query) use ($field, $value){
        if($field == 'original_title'){
          $query->whereRaw(DB::getTablePrefix() . "ca.{$field} LIKE '%{$value}%'"); // PWS#finale
          $query->whereIn('ca.releasetype', [config('constants.release.tipo.poster')]);
        } else{
          $query->where('ca.' . $field, '=', $value);
          $query->whereNotIn('ca.releasetype', [config('constants.release.tipo.poster')]);
        }
      })
      ->where('ca.release_status', DB::raw(1))
      ->where('ca.release_is_visible', DB::raw(1))
      ->groupBy('ca.id')
      ->paginate(6);

      return $releases;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $release = $this->customerReleaseRepository->find($id);

       //return view($this->_config['view'], compact('release'));
       $language = DB::table('language')->orderBy('name', 'asc')->get();
       $masters = DB::table('master')->orderBy('master_maintitle', 'asc')->get(); // PWS#8-link-master
       $master_selezionato = (int) DB::table('releases AS r')
         ->where('r.id', $id)
         ->addSelect('r.master_id')
         ->get()
         ->first()->master_id; // PWS#8-link-master

        $releasetype = DB::table('releaseType')->orderBy('name', 'asc')->get();
        return view($this->_config['view'], array_merge(compact('release','language','releasetype','masters','master_selezionato'), [ // PWS#8-link-master
            'defaultCountry' => $release->country,
            'defaultLanguage' =>$release->language,
            'defaultType' =>$release->releasetype,
        ]));
    }

    /**
     * Edit's the pre made resource of customer called address.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        // die("5");
       //request()->merge(['address1' => implode(PHP_EOL, array_filter(request()->input('address1')))]);

        // $this->validate(request(), [
        //     'company_name' => 'string',
        //     'address1'     => 'string|required',
        //     'country'      => 'string|required',
        //     'state'        => 'string|required',
        //     'city'         => 'string|required',
        //     'postcode'     => 'required',
        //     'phone'        => 'required',
        //   //  'vat_id'       => new VatIdRule(),
        // ]);
        $this->validate(request(), [
            'master_id'        => 'required',
            'original_title'        => 'required',
            // 'other_title'            => '',
            'country'                => 'required',
            'release_year'           => ['required','size:4'],
            'release_distribution'   => 'required',
            'releasetype'           => 'required',
            'language'       => 'required',
        ]); // PWS#8-link-master

        $data = collect(request()->input())->except('_token')->toArray();

        $release = $this->customerReleaseRepository->find($id);

        if ($release) {
            $this->customerReleaseRepository->update($data, $id);

            session()->flash('success', trans('admin::app.customers.releases.success-update'));

            // return redirect()->route('admin.customer.releases.index', ['id' => $release->customer_id]); // PWS#8-link-master
            return redirect()->route('customer.release.index', ['id' => $release->customer_id]);
        }
        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->customerReleaseRepository->delete($id);

        session()->flash('success', trans('admin::app.customers.releases.success-delete'));

        return redirect()->route('customer.release.index', ['id' => $this->customer->id]);

        // return response()->json([
        //     'redirect' => false,
        //     'message' => trans('admin::app.customers.releases.success-delete')
        // ]);
    }

    /**
     * Mass delete the customer's addresses.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function massDestroy($id)
    {
        $Ids = explode(',', request()->input('indexes'));

        foreach ($Ids as $releaseId) {
            $this->customerReleaseRepository->delete($releaseId);
        }

        session()->flash('success', trans('admin::app.customers.releases.success-mass-delete'));

        return redirect()->route($this->_config['redirect'], ['id' => $id]);
    }
}
