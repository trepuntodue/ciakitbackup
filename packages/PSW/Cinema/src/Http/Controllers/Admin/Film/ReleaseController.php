<?php

namespace PSW\Cinema\Http\Controllers\Admin\Film;

use Illuminate\Http\Request;
use PSW\Cinema\DataGrids\ReleaseDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use PSW\Cinema\Core\Repositories\ChannelRepository;
use Illuminate\Support\Facades\DB;
use PSW\Cinema\Film\Repositories\ReleaseRepository;
use PSW\Cinema\Film\Facades\ReleaseImage;
use PSW\Cinema\Film\Repositories\ReleaseImageRepository;
use PSW\Cinema\Film\Repositories\ReleaseMediaRepository;
use PSW\Cinema\Film\Models\ReleasesPage;
use PSW\Cinema\Film\Helpers\AbstractRelease;
use PSW\Cinema\Core\Contracts\Validations\Slug;
use Webkul\Customer\Repositories\CustomerRepository;
use PSW\Cinema\Customer\Repositories\CustomerReleaseRepository;

class ReleaseController extends Controller
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
     * @param \PSW\Cinema\Film\Repositories\ReleaseRepository  $releaseRepository
     * @param  \PSW\Cinema\Film\Repositories\ReleaseImageRepository  $releaseImageRepository
     */
    public function __construct(
        protected ReleaseRepository $releaseRepository,
        protected AbstractRelease $releaseImage,
        protected CustomerRepository $customerRepository,

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
            return app(ReleaseDataGrid::class)->toJson();
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
        // $customerGroup = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);

        $release = DB::table('releases')->get()->pluck('id');

        return view($this->_config['view'], compact( 'release'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = $req->all();
        //  echo 'entro nello store';
        // echo '<pre>';print_r($data);
        // die();
        // foreach ($data as $key => $value){
        //     if(is_array($value) && $key!='images'){

        //         $dt=implode(",",$value);

        //         $data[$key]=$dt;
        //     }
        // }
        $this->validate(request(), [
           // 'release_maintitle'  => ['required', 'unique:release,release_maintitle'],
            'release_year'       =>['required','size:4'],
            //'url_key'           => ['required', 'unique:release,url_key', new Slug],
        ]);
      if ($release=$this->releaseRepository->create($data)) {

           // session()->flash('success', trans('admin::app.cinema.release.create-success'));
           // return view($this->_config['view']);
        //    echo '<pre>';print_r($release);
        //    die("sotre");
           return redirect()->route($this->_config['redirect'], ['id' => $release->id]);

          }

         session()->flash('error', trans('admin::app.cinema.release.create-error'));

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
        // echo 'ID '.$id;

       // $release = DB::table('releases')->where('id', '=', $id)->get();
        $release=$this->releaseRepository->findOneByField('id',$id);
        $language = DB::table('language')->where('status', 1)->orderBy('name', 'asc')->get();
        $array_lingue_selezionate = DB::table('language')
          ->leftJoin('release_relazioni', 'language.id', '=', 'release_relazioni.elemento_id')
          ->where('release_relazioni.release_id', $id)
          ->where('release_relazioni.tipo_relazione',config('constants.release.relazioni.lingua'))
          ->addSelect('language.name')
          ->addSelect('language.id')
          ->orderBy('language.name', 'asc')
          ->get()
          ->toArray();
        $lingue_selezionate = array();
        foreach ($array_lingue_selezionate as $key => $lingua) {
          array_push($lingue_selezionate,$lingua->id);
        }
        $masters = DB::table('master')->orderBy('master_maintitle', 'asc')->get(); // PWS#8-link-master
        $master_selezionato = (int) DB::table('releases AS r')
          ->where('r.id', $id)
          ->addSelect('r.master_id')
          ->get()
          ->first()->master_id; // PWS#8-link-master

        $master_selezionato_object = DB::table('master')->where('master_id', $master_selezionato)->get()->first();

        $release_formato = DB::table('release_formato')->where('status', 1)->orderBy('nome', 'asc')->get();
        $release_aspect_ratio = DB::table('release_aspect_ratio')->where('status', 1)->orderBy('nome', 'asc')->get();
        $release_camera_format = DB::table('release_camera_format')->where('status', 1)->orderBy('nome', 'asc')->get();
        $release_region_code = DB::table('release_region_code')->where('status', 1)->orderBy('nome', 'asc')->get();
        $release_tipologia = DB::table('release_tipologia')->where('status', 1)->orderBy('nome', 'asc')->get();
        $release_canali_audio = DB::table('release_canali_audio')->where('status', 1)->orderBy('nome', 'asc')->get();
        $release_poster_tipo = DB::table('release_poster_tipo')->where('status', 1)->orderBy('nome', 'asc')->get(); // PWS#video-poster-2
        $release_poster_formato = DB::table('release_poster_formato')->where('status', 1)->orderBy('nome', 'asc')->get();
        $release_poster_misure = DB::table('release_poster_misure')->where('status', 1)->orderBy('nome', 'asc')->get();

        // $address = $this->customerAddressRepository->find($id);
         //$release = $this->releaseRepository->find($id);
        // $customerGroup = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);
        //$channelName = $this->channelRepository->all();

       //return view($this->_config['view'], compact( 'release','language'));
        return view($this->_config['view'], array_merge(compact('release','language','masters','master_selezionato','release_formato','release_aspect_ratio','release_camera_format','release_region_code','release_tipologia','release_canali_audio','release_poster_tipo','release_poster_formato','release_poster_misure','master_selezionato_object','lingue_selezionate'), [ // PWS#8-link-master
            'defaultCountry' =>'',
            'defaultLanguage' =>'',
            'defaultGeneri' =>'',
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        //die("6");
        $this->validate(request(), [
            'release_year'    => ['required','size:4'],
        ]);
        $data = request()->all();
// echo '<pre>';print_R($data);
// die("update");
 //die("ciao");
        // $data['status'] = ! isset($data['status']) ? 0 : 1;

        // $data['is_suspended'] = ! isset($data['is_suspended']) ? 0 : 1;

        //$this->customerRepository->update($data, $id);

        // $release= $this->releaseRepository->update($data, $id);
        // $release= $this->releaseRepository->uploadImages($data, $release,'images');

        $release= $this->releaseRepository->update($data, $id);

        $release= $this->releaseRepository->uploadImages($data, $release,'images');


        session()->flash('success', trans('admin::app.cinema.release.update-success'));

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
        $customer = $this->customerRepository->findorFail($id);

        try {
            if (! $this->customerRepository->checkIfCustomerHasOrderPendingOrProcessing($customer)) {
                $this->customerRepository->delete($id);

                return response()->json(['message' => trans('admin::app.response.delete-success', ['name' => 'Customer'])]);
            }

            return response()->json(['message' => trans('admin::app.response.order-pending', ['name' => 'Customer'])], 400);
        } catch (\Exception $e) {}

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Customer'])], 400);
    }

    /**
     * To load the note taking screen for the customers.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function createNote($id)
    {
        $customer = $this->customerRepository->find($id);

        return view($this->_config['view'])->with('customer', $customer);
    }

    /**
     * To store the response of the note in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeNote()
    {
        $this->validate(request(), [
            'notes' => 'string|nullable',
        ]);

        $customer = $this->customerRepository->find(request()->input('_customer'));

        $noteTaken = $customer->update(['notes' => request()->input('notes')]);

        if ($noteTaken) {
            session()->flash('success', 'Note taken');
        } else {
            session()->flash('error', 'Note cannot be taken');
        }

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To mass update the customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function massUpdate()
    {
        $customerIds = explode(',', request()->input('indexes'));
        $updateOption = request()->input('update-options');

        foreach ($customerIds as $customerId) {
            $customer = $this->customerRepository->find($customerId);

            $customer->update(['status' => $updateOption]);
        }

        session()->flash('success', trans('admin::app.customers.customers.mass-update-success'));

        return redirect()->back();
    }

    /**
     * To mass delete the customer.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $customerIds = explode(',', request()->input('indexes'));

        if (! $this->customerRepository->checkBulkCustomerIfTheyHaveOrderPendingOrProcessing($customerIds)) {

            foreach ($customerIds as $customerId) {
                $this->customerRepository->deleteWhere(['id' => $customerId]);
            }

            session()->flash('success', trans('admin::app.customers.customers.mass-destroy-success'));

            return redirect()->back();
        }

        session()->flash('error', trans('admin::app.response.order-pending', ['name' => 'Customers']));
        return redirect()->back();
    }

    /**
     * Retrieve all invoices from customer.
     *
     * @param  int  $id
     * @return \Webkul\Admin\DataGrids\CustomersInvoicesDataGrid
     */
    public function invoices($id)
    {
        if (request()->ajax()) {
          // return app(CustomersInvoicesDataGrid::class)->toJson();
        }
    }

    /**
     * Retrieve all orders from customer.
     *
     * @param  int  $id
     * @return \Webkul\Admin\DataGrids\CustomerOrderDataGrid
     */
    public function orders($id)
    {
        // if (request()->ajax()) {
        //     return app(CustomerOrderDataGrid::class)->toJson();
        // }

        // $customer = $this->customerRepository->find(request('id'));

        // return view($this->_config['view'], compact('customer'));
    }
    /**
     * The related products that belong to the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function related_releases()
    {
        die("related_releases");
        return $this->release->related_releases()->get();
    }
       /**
     * Uploads downloadable file.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function uploadLink($id)
    {
        die("uploadLink");
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
        die("uploadSample");
        return response()->json(
            $this->productDownloadableSampleRepository->upload(request()->all(), $id)
        );
    }
}
