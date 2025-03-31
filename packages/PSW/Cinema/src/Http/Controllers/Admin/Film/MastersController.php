<?php

namespace PSW\Cinema\Http\Controllers\Admin\Film;

use Illuminate\Http\Request;
use PSW\Cinema\DataGrids\MasterDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use PSW\Cinema\Core\Repositories\ChannelRepository;
use Illuminate\Support\Facades\DB;
use PSW\Cinema\Film\Repositories\MastersRepository;
use PSW\Cinema\Film\Facades\MasterImage;
use PSW\Cinema\Film\Repositories\MasterImageRepository;
use PSW\Cinema\Film\Repositories\MasterMediaRepository;
use PSW\Cinema\Film\Models\MasterPage;
use PSW\Cinema\Film\Helpers\AbstractMaster;
use PSW\Cinema\Core\Contracts\Validations\Slug;
use Mail; // PWS#chiusura
use Illuminate\Mail\Mailables\Address; // PWS#chiusura
use Illuminate\Mail\Mailables\Envelope; // PWS#chiusura
use PSW\Cinema\Shop\Mail\NewMasterFromCustomerApproved; // PWS#chiusura



class MastersController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @param \PSW\Cinema\Film\Repositories\MastersRepository  $mastersRepository
     * @param  \PSW\Cinema\Film\Repositories\MasterImageRepository  $masterImageRepository
     */
    public function __construct(
       protected MastersRepository $mastersRepository,
       protected AbstractMaster $masterImage
        )
    {
        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        if (request()->ajax()) {
            return app(MasterDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {

        $language = DB::table('language')->where('status', 1)->orderBy('name', 'asc')->get();
        $generi = DB::table('generi')->where('status', 1)->orderBy('generi_name', 'asc')->get();

        $attori = DB::table('attori')->where('status', 1)->orderBy('attori_nome', 'asc')->get();
        $compositori = DB::table('compositori')->where('status', 1)->orderBy('compo_nome', 'asc')->get();
        $registi = DB::table('registi')->where('status', 1)->orderBy('registi_nome', 'asc')->get();
        $subgeneri =DB::table('sottogeneri')->where('subge_status', 1)->orderBy('subge_name', 'asc')->get();
        $casaproduzione = DB::table('casaproduzione')->where('status', 1)->orderBy('casa_nome', 'asc')->get();
        $sceneggiatori = DB::table('sceneggiatori')->where('status', 1)->orderBy('scene_nome', 'asc')->get();


        return view($this->_config['view'], array_merge(compact('language','generi','attori', 'compositori','registi','casaproduzione','sceneggiatori','subgeneri'), [
            'defaultCountry' =>'',
            'defaultLanguage' =>'',
            'defaultGeneri' =>'',
        ]));
        //die("muori male");
      // return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $data = $req->all();

        // $id_animazione = 3; // PWS#9 // PWS#9-2

        $attori_required = 'required';
        if(isset($data['master_genres']) && (in_array(config('constants.master.generi.animazione'),$data['master_genres']) || in_array(config('constants.master.generi.documentario'),$data['master_genres']))){ // PWS#9-2
          $attori_required = '';
        }
        if(!isset($data['master_actors'])){
          $data['master_actors'] = NULL;
        }
        // END PWS#9

        // PWS#04-23v2
        $scriptwriters_required = 'required';
        if(isset($data['master_genres']) && in_array(config('constants.master.generi.documentario'),$data['master_genres'])){ // PWS#9-2
          $scriptwriters_required = '';
        }
        if(!isset($data['master_scriptwriters'])){
          $data['master_scriptwriters'] = NULL;
        }

        //  echo 'entro nello store';
        // echo '<pre>';print_r($data);
        // die();
        foreach ($data as $key => $value){
            if(is_array($value) && $key!='images'){

                $dt=implode(",",$value);

                $data[$key]=$dt;
            }
        }



        $this->validate(request(), [
            'master_maintitle'  => ['required'],
            'master_year'       =>['required','size:4'],
            'url_key'           => ['required', 'unique:master,url_key', new Slug],
            'master_actors'     => [$attori_required], // PWS#9
            'master_scriptwriters' => [$scriptwriters_required],
        ]);

      if ($master=$this->mastersRepository->create($data)) {

           // session()->flash('success', trans('admin::app.cinema.master.create-success'));
           // return view($this->_config['view']);
        //    echo '<pre>';print_r($master);
        //    die("sotre");

        if(isset($data['customer_id']) && isset($data['master_is_visible']) && $data['master_is_visible'] == 1){ // PWS#chiusura
          $user = DB::table('customers')->where('id', intval($data['customer_id']))->first();
          $user_email = $user->email;
          try {
              Mail::queue(new NewMasterFromCustomerApproved($master->master_id, $data['url_key'], $data['master_maintitle'], $user_email));
          } catch (\Exception $e) {
              report($e);
          }
        }

           return redirect()->route($this->_config['redirect'], ['id' => $master->master_id]);

          }

         session()->flash('error', trans('admin::app.cinema.master.create-error'));

          return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {

        $master=$this->mastersRepository->findOneByField('master_id',$id);
        $language = DB::table('language')->where('status', 1)->orderBy('name', 'asc')->get();
        $generi = DB::table('generi')->where('status', 1)->orderBy('generi_name', 'asc')->get();
        // START PWS#7-2
        $array_generi_selezionati = DB::table('master_relazioni')
          ->leftJoin('generi', 'generi.id', '=', 'master_relazioni.elemento_id')
          ->where('master_relazioni.master_id', $id)
          ->where('master_relazioni.tipo_relazione',config('constants.master.relazioni.generi'))
          ->orderBy('generi_name', 'asc')
          ->addSelect('master_relazioni.elemento_id')
          ->get()
          ->toArray();
        $generi_selezionati = array();
        foreach ($array_generi_selezionati as $key => $gen) {
          array_push($generi_selezionati,$gen->elemento_id);
        }

        $array_sottogeneri_selezionati = DB::table('master_relazioni')
          ->leftJoin('sottogeneri', 'sottogeneri.id', '=', 'master_relazioni.elemento_id')
          ->where('master_relazioni.master_id', $id)
          ->where('master_relazioni.tipo_relazione',config('constants.master.relazioni.sottogeneri'))
          ->orderBy('subge_name', 'asc')
          ->addSelect('master_relazioni.elemento_id')
          ->get()
          ->toArray();
        $sottogeneri_selezionati = array();
        foreach ($array_sottogeneri_selezionati as $key => $gen) {
          array_push($sottogeneri_selezionati,$gen->elemento_id);
        }

        $array_country_selezionati = DB::table('countries')
          ->leftJoin('master_relazioni', 'countries.id', '=', 'master_relazioni.elemento_id')
          ->where('master_relazioni.master_id', $id)
          ->where('master_relazioni.tipo_relazione',config('constants.master.relazioni.countries'))
          ->addSelect('countries.code')
          ->orderBy('countries.name', 'asc')
          ->get()
          ->toArray();
        $country_selezionati = array();
        foreach ($array_country_selezionati as $key => $country) {
          array_push($country_selezionati,$country->code);
        }
        $countries = DB::table('countries')->orderBy('name', 'asc')->get();

        $array_registi_selezionati = DB::table('registi')
          ->leftJoin('master_relazioni', 'registi.id', '=', 'master_relazioni.elemento_id')
          ->where('master_relazioni.master_id', $id)
          ->where('master_relazioni.tipo_relazione',config('constants.master.relazioni.registi'))
          ->addSelect('registi.registi_nome_cognome')
          ->addSelect('registi.id')
          ->orderBy('registi.registi_nome_cognome', 'asc')
          ->get()
          ->toArray();
        $registi_selezionati = array();
        foreach ($array_registi_selezionati as $key => $registi) {
          array_push($registi_selezionati,$registi->id);
        }

        $array_sceneggiatori_selezionati = DB::table('sceneggiatori')
          ->leftJoin('master_relazioni', 'sceneggiatori.id', '=', 'master_relazioni.elemento_id')
          ->where('master_relazioni.master_id', $id)
          ->where('master_relazioni.tipo_relazione',config('constants.master.relazioni.sceneggiatori'))
          ->addSelect('sceneggiatori.scene_nome_cognome')
          ->addSelect('sceneggiatori.id')
          ->orderBy('sceneggiatori.scene_nome_cognome', 'asc')
          ->get()
          ->toArray();
        $sceneggiatori_selezionati = array();
        foreach ($array_sceneggiatori_selezionati as $key => $sceneggiatore) {
          array_push($sceneggiatori_selezionati,$sceneggiatore->id);
        }

        $array_compositori_selezionati = DB::table('compositori')
          ->leftJoin('master_relazioni', 'compositori.id', '=', 'master_relazioni.elemento_id')
          ->where('master_relazioni.master_id', $id)
          ->where('master_relazioni.tipo_relazione',config('constants.master.relazioni.compositori'))
          ->addSelect('compositori.compo_nome_cognome')
          ->addSelect('compositori.id')
          ->orderBy('compositori.compo_nome_cognome', 'asc')
          ->get()
          ->toArray();
        $compositori_selezionati = array();
        foreach ($array_compositori_selezionati as $key => $compositore) {
          array_push($compositori_selezionati,$compositore->id);
        }

        $array_casaproduzione_selezionati = DB::table('casaproduzione')
          ->leftJoin('master_relazioni', 'casaproduzione.id', '=', 'master_relazioni.elemento_id')
          ->where('master_relazioni.master_id', $id)
          ->where('master_relazioni.tipo_relazione',config('constants.master.relazioni.casaproduzione'))
          ->addSelect('casaproduzione.casa_nome')
          ->addSelect('casaproduzione.id')
          ->orderBy('casaproduzione.casa_nome', 'asc')
          ->get()
          ->toArray();
        $casaproduzione_selezionati = array();
        foreach ($array_casaproduzione_selezionati as $key => $casaproduzione) {
          array_push($casaproduzione_selezionati,$casaproduzione->id);
        }

        $array_attori_selezionati = DB::table('attori')
          ->leftJoin('master_relazioni', 'attori.id', '=', 'master_relazioni.elemento_id')
          ->where('master_relazioni.master_id', $id)
          ->where('master_relazioni.tipo_relazione',config('constants.master.relazioni.attori'))
          ->addSelect('attori.attori_nome_cognome')
          ->addSelect('attori.id')
          ->orderBy('attori.attori_nome_cognome', 'asc')
          ->get()
          ->toArray();
        $attori_selezionati = array();
        foreach ($array_attori_selezionati as $key => $attore) {
          array_push($attori_selezionati,$attore->id);
        }
        // END PWS#7-2

        // START PWS#10-lang
        $array_lingue_selezionate = DB::table('language')
          ->leftJoin('master_relazioni', 'language.id', '=', 'master_relazioni.elemento_id')
          ->where('master_relazioni.master_id', $id)
          ->where('master_relazioni.tipo_relazione',config('constants.master.relazioni.lingua'))
          ->addSelect('language.name')
          ->addSelect('language.id')
          ->orderBy('language.name', 'asc')
          ->get()
          ->toArray();
        $lingue_selezionate = array();
        foreach ($array_lingue_selezionate as $key => $lingua) {
          array_push($lingue_selezionate,$lingua->id);
        }
        // END PWS#10-lang

        $attori = DB::table('attori')->where('status', 1)->orderBy('attori_nome', 'asc')->get();
        $compositori = DB::table('compositori')->where('status', 1)->orderBy('compo_nome', 'asc')->get();
        $registi = DB::table('registi')->where('status', 1)->orderBy('registi_nome', 'asc')->get();
        $subgeneri =DB::table('sottogeneri')->where('subge_status', 1)->orderBy('subge_name', 'asc')->get();
        $casaproduzione = DB::table('casaproduzione')->where('status', 1)->orderBy('casa_nome', 'asc')->get();
        $sceneggiatori = DB::table('sceneggiatori')->where('status', 1)->orderBy('scene_nome', 'asc')->get();
// echo '<pre>';
// print_r($master);
// die();
        return view($this->_config['view'], array_merge(compact('master','language','lingue_selezionate','generi','generi_selezionati','attori','attori_selezionati', 'compositori','compositori_selezionati','registi','registi_selezionati','casaproduzione','casaproduzione_selezionati','sceneggiatori','sceneggiatori_selezionati','subgeneri','sottogeneri_selezionati','country_selezionati','countries'), [
            'defaultCountry' =>'',
            'defaultLanguage' =>'',
            'defaultGeneri' =>'',
        ])); // PWS#7 | PWS#10-lang
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        // $id_animazione = 3; // PWS#9 // PWS#9-2

        $data = request()->all(); // START PWS#9
        $attori_required = 'required';
        if(isset($data['master_genres']) && (in_array(config('constants.master.generi.animazione'),$data['master_genres']) || in_array(config('constants.master.generi.documentario'),$data['master_genres']))){ // PWS#9-2
          $attori_required = '';
        }
        if(!isset($data['master_actors'])){
          $data['master_actors'] = NULL;
        }
        // END PWS#9


        //echo("arrivo qui1");
        $this->validate(request(), [
           // 'images'            => ['dimensions:ratio=2/3'],
            //'images'             => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:ratio=2/3',
           // 'master_maintitle'  => ['required', 'unique:master,master_maintitle'],
            'master_year'         => ['required','size:4'],
            'master_actors'     => [$attori_required], // PWS#9
           // 'url_key'           => ['required', 'unique:master,url_key', new Slug],

            // 'last_name'     => 'string|required',
            // 'gender'        => 'required',
            // 'email'         => 'required|unique:customers,email,' . $id,
            // 'date_of_birth' => 'date|before:today',
        ]);
    //    echo '<pre>';print_r($data);
    //    die();
        foreach ($data as $key => $value){
            if(is_array($value) && $key!='images'){
                $dt=implode(",",$value);
                $data[$key]=$dt;
            }
        }


        // $data['master_is_visible'] = ! isset($data['master_is_visible']) ? 0 : 1;

        // $data['master_vt18'] = ! isset($data['master_vt18']) ? 0 : 1;

        $master= $this->mastersRepository->update($data, $id);
        $master= $this->mastersRepository->uploadImages($data, $master,'images');
        //$master= $this->masterImage->update($data, $id, 'id');
       //$this->uploadImages($data, $master);
       //$this->masterImage->uploadImages($data, $master);
        session()->flash('success', trans('admin::app.cinema.master.update-success'));

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

        try {
            $this->mastersRepository->delete($id);

            session()->flash('success', trans('admin::app.cinema.master.delete-success'));

            return response()->json(['message' => trans('admin::app.cinema.master.delete-success')], 200);
        } catch(\Exception $e) {
            session()->flash('error', trans('admin::app.cinema.master.delete-failed'));
        }

        return response()->json(['message' =>  trans('admin::app.cinema.master.delete-failed')], 400);
        }

    /**
     * To mass update the master.
     *
     * @return \Illuminate\Http\Response
     */
    public function massUpdate()
    {
        $masterIds = explode(',', request()->input('indexes'));
        $updateOption = request()->input('update-options');

        foreach ($masterIds as $masterId) {
            $master = $this->mastersRepository->find($masterId);

            $master->update(['status' => $updateOption]);
        }

        session()->flash('success', trans('admin::app.cinema.master.mass-update-success'));

        return redirect()->back();
    }

    /**
     * To mass delete the master.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $masterIds = explode(',', request()->input('indexes'));

        if (! $this->mastersRepository->checkBulkMasterIfTheyHaveOrderPendingOrProcessing($masterIds)) {

            foreach ($masterIds as $masterId) {
                $this->mastersRepository->deleteWhere(['id' => $masterId]);
            }

            session()->flash('success', trans('admin::app.cinema.master.mass-destroy-success'));

            return redirect()->back();
        }

        session()->flash('error', trans('admin::app.cinema.master.mass-destroy-success'));
        return redirect()->back();
    }
    /**
     * Uploads downloadable file.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uploadLink($id)
    {
        return response()->json(
            $this->productDownloadableLinkRepository->upload(request()->all(), $id)
        );
    }
    /**
     * Uploads downloadable sample file.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uploadSample($id)
    {
        return response()->json(
            $this->productDownloadableSampleRepository->upload(request()->all(), $id)
        );
    }

}
