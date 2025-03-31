<?php

// PWS#chiusura

namespace PSW\Cinema\Http\Controllers\Admin\Customer;

use Mail;
use PSW\Cinema\DataGrids\ReleaseDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use PSW\Cinema\Shop\Mail\NewMasterFromCustomer;
use Webkul\Customer\Repositories\CustomerRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // PWS#230101
use Webkul\Customer\Models\Customer;
use Illuminate\Support\Facades\Request;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;


class MasterController extends Controller
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

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function create()
    {
      $action = isset($filters['action']) ? $filter['actions'] : false;

      $customer = Auth::user();

      $customer_id = $this->customer->id;

      $generi = DB::table('generi as g')
        ->where('g.status', 1)
        ->orderBy('g.generi_name', 'asc')->get();

      $sottogeneri = DB::table('sottogeneri as sg')
        ->where('sg.subge_status', 1)
        ->orderBy('sg.subge_name', 'asc')->get();

      $countries = DB::table('countries as c')
        ->orderBy('c.name', 'asc')->get();

      $lingue = DB::table('language as l')
        ->where('l.status', 1)
        ->orderBy('l.name', 'asc')->get();

      return view($this->_config['view'], compact('customer_id', 'generi', 'sottogeneri', 'countries', 'lingue'));
    }

    public function send()
    {
      $data = request()->all();
      $attachments = array();
      if(isset($data['master_images'])){
        foreach (request()->file('master_images') as $key => $img) {
          $filename = pathinfo($img)['filename'] . $img->getClientOriginalName();
          $img->move(public_path('upload'), $filename);
          $attachments[$key] = public_path('upload') . "/" . $filename;
        }
        unset($data['master_images']);
      }
      try {
          Mail::queue(new NewMasterFromCustomer($data, $attachments));
      } catch (\Exception $e) {
          report($e);
      }

      try {
          Mail::queue(new NewMasterFromCustomer(false));
      } catch (\Exception $e) {
          report($e);
      }
      session()->flash('success', trans('shop::app.customer.account.master.create.success'));
       return redirect()->route('customer.release.index');
    }
}
