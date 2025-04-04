<?php
// PWS#13-release
namespace PSW\Cinema\Http\Controllers\Admin\Customer;

use Mail;
use Webkul\Admin\DataGrids\CustomerDataGrid;
use Webkul\Admin\DataGrids\CustomerOrderDataGrid;
use Webkul\Admin\DataGrids\CustomersInvoicesDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Mail\NewCustomerNotification;
use Webkul\Core\Repositories\ChannelRepository;
use Webkul\Customer\Repositories\CustomerAddressRepository;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Illuminate\Support\Facades\Auth; // PWS#13-release
use Illuminate\Support\Facades\DB; // PWS#13-release

class CustomerController extends Controller
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
     * @param \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @param  \Webkul\Customer\Repositories\CustomerAddressRepository  $customerAddressRepository
     * @param \Webkul\Customer\Repositories\CustomerGroupRepository  $customerGroupRepository
     * @param \Webkul\Core\Repositories\ChannelRepository  $channelRepository
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CustomerAddressRepository $customerAddressRepository,
        protected CustomerGroupRepository $customerGroupRepository,
        protected ChannelRepository $channelRepository
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
            return app(CustomerDataGrid::class)->toJson();
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
        $customerGroup = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);

        $channelName = $this->channelRepository->all();

        return view($this->_config['view'], compact('customerGroup', 'channelName'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'first_name'    => 'string|required',
            'last_name'     => 'string|required',
            'gender'        => 'required',
            'email'         => 'required|unique:customers,email',
            'date_of_birth' => 'date|before:today',
        ]);

        $data = request()->all();

        $password = rand(100000, 10000000);

        $data['password'] = bcrypt($password);

        $data['is_verified'] = 1;

        $customer = $this->customerRepository->create($data);

        try {
            $configKey = 'emails.general.notifications.emails.general.notifications.customer';
            if (core()->getConfigData($configKey)) {
                Mail::queue(new NewCustomerNotification($customer, $password));
            }
        } catch (\Exception $e) {
            report($e);
        }

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Customer']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $customer = $this->customerRepository->findOrFail($id);
        $address = $this->customerAddressRepository->find($id);
        $customerGroup = $this->customerGroupRepository->findWhere([['code', '<>', 'guest']]);
        $channelName = $this->channelRepository->all();

        return view($this->_config['view'], compact('customer', 'address', 'customerGroup', 'channelName'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $this->validate(request(), [
            'first_name'    => 'string|required',
            'last_name'     => 'string|required',
            'gender'        => 'required',
            'email'         => 'required|unique:customers,email,' . $id,
            'date_of_birth' => 'date|before:today',
        ]);

        $data = request()->all();

        $data['status'] = ! isset($data['status']) ? 0 : 1;

        $data['is_suspended'] = ! isset($data['is_suspended']) ? 0 : 1;

        $this->customerRepository->update($data, $id);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Customer']));

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
            return app(CustomersInvoicesDataGrid::class)->toJson();
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
        if (request()->ajax()) {
            return app(CustomerOrderDataGrid::class)->toJson();
        }

        $customer = $this->customerRepository->find(request('id'));

        return view($this->_config['view'], compact('customer'));
    }

    // START PWS#13-release
    function addToCollection(){
        if(Auth::check()){
          $element_id = request()->input('element_id');
          $user_id = Auth::id();
          $tipo = request()->input('tipo');
          $table = request()->input('table');
          $toDelete = filter_var(request()->input('toDelete'), FILTER_VALIDATE_BOOLEAN);
          
          switch ($tipo) {
            case 'collection':
              $from = $table . '_user';
              break;
            case 'favorite':
              $from = $table . '_user_favorite';
              break;
          }
  
          if($table){
            $exists = DB::table($from)
            ->where($table . '_id', '=', DB::raw($element_id))
            ->where('user_id', '=', DB::raw($user_id))
            ->get()->all();
    
            if(count($exists) && $toDelete){
              DB::table($from)
                ->where($table . '_id', '=', DB::raw($element_id))
                ->where('user_id', '=', DB::raw($user_id))
                ->delete();
              
              return response()->json([ 'response' => true ]);
            } else if(!count($exists) && !$toDelete){
              DB::table($from)->insert(
                array(
                $table . '_id' => $element_id,
                'user_id'   => $user_id,
                )
              );
    
              return response()->json([ 'response' => true ]);
            }
          }
        }
        return response()->json([ 'response' => false ]);
      }
      // END PWS#13-release
}
