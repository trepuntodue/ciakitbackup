<?php
namespace PSW\Cinema\Http\Controllers\Admin\Film;

use Illuminate\Http\Request;
use PSW\Cinema\DataGrids\ActoryDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Repositories\ChannelRepository;
use Illuminate\Support\Facades\DB;
use PSW\Cinema\Film\Repositories\ActoryRepository;


class ActoryController extends Controller
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
     * @param \PSW\Cinema\Film\Repositories\ActoryRepository  $subgeneriRepository
     */
    public function __construct(
       protected ActoryRepository $actoryRepository 
        ){
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
            return app(ActoryDataGrid::class)->toJson();
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
      
        $attori = DB::table('attori')->orderBy('attori_nome', 'asc')->get();
       // echo '<pre>';print_r($generi);  
        return view($this->_config['view'], compact('attori'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        $data = $req->all();
        //  echo 'entro nello store';
       // echo '<pre>';print_r($data);
      
        //die();
        $data['attori_nome_cognome']=$data['attori_nome'].' '.$data['attori_cognome'].' '.$data['attori_alias'];
        if ($this->actoryRepository->create($data)) {
            session()->flash('success', trans('admin::app.cinema.attore.create-success'));
           return redirect()->route($this->_config['redirect']);
        }
            session()->flash('error', trans('admin::app.cinema.attore.create-error'));        
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

        $attori=$this->actoryRepository->findOneByField('id',$id);

        return view($this->_config['view'], compact('attori'));
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
            'attori_nome'    => 'string|required',
            // 'last_name'     => 'string|required',
            // 'gender'        => 'required',
            // 'email'         => 'required|unique:customers,email,' . $id,
            // 'date_of_birth' => 'date|before:today',
        ]);

        $data = request()->all();
        //echo '<pre>';print_r($data);

        // $data['master_is_visible'] = ! isset($data['master_is_visible']) ? 0 : 1;

        // $data['master_vt18'] = ! isset($data['master_vt18']) ? 0 : 1;
        $data['attori_nome_cognome']=$data['attori_nome'].' '.$data['attori_cognome'].' '.$data['attori_alias'];

        $this->actoryRepository->update($data, $id);

        session()->flash('success', trans('admin::app.cinema.attore.update-success'));

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
            $this->actoryRepository->delete($id);
           
            session()->flash('success', trans('admin::app.cinema.master.delete-success', ['name' => 'Master']));

            return response()->json(['message' => true], 200);
        } catch(\Exception $e) {
            session()->flash('error', trans('admin::app.cinema.master.delete-failed', ['name' => 'Master']));
        }

        return response()->json(['message' => false], 400);
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
}
