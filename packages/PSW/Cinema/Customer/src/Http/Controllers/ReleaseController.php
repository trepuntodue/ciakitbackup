<?php

namespace PSW\Cinema\Customer\Http\Controllers;

use Webkul\Customer\Models\Customer;
use PSW\Cinema\Customer\Http\Requests\CustomerReleaseRequest;
use PSW\Cinema\Customer\Repositories\CustomerReleaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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
     * @param  \PSW\Cinema\Customer\Repositories\CustomerReleaseRepository  $customerReleaseRepository
     * @return void
     */
    public function __construct(protected CustomerReleaseRepository $customerReleaseRepository)
    {
        $this->_config = request('_config');

        $this->customer = auth()->guard('customer')->user();
    }

    /**
     * Release route index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $customer_id = $this->customer->id;
        // die("casdas");
       $releases = DB::table('releases')->where('customer_id', '=', $customer_id)->orderBy('id', 'desc')->get();


        // return view($this->_config['view'], ['releases' => $releases]);
        return view($this->_config['view'])->with('releases', $releases);

    }

    /**
     * Show the release create form.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $language = DB::table('language')->orderBy('name', 'asc')->get();
        $releasetype = DB::table('releaseType')->orderBy('name', 'asc')->get();
        return view($this->_config['view'], array_merge(compact('language','releasetype'), [
            'defaultCountry' =>'',
            'defaultLanguage' =>'',
            'defaultType' =>'',
        ]));
        // return view($this->_config['view'], array_merge(compact('language','releasetype'), [
        //      'defaultCountry' => config('app.default_country'),
        //      'defaultLanguage' => 'Seleziona Lingua',
        //      'defaultType' => 'Seleziona Tipo',
        // ]));
    }

    /**
     * Create a new release for customer.
     *
     * @return view
     */
    public function store(Request $req)
    {

      $data = $req->all();
        // die("muoioooo");

        $data['customer_id'] = $this->customer->id;

        // if ($this->customer->releases->count() == 0) {
        //    $data['default_release'] = 1;
        // }
        if ($this->customerReleaseRepository->create($data)) {
            session()->flash('success', trans('shop::app.customer.account.release.create.success'));

            return redirect()->route($this->_config['redirect']);
        }

        session()->flash('error', trans('shop::app.customer.account.release.create.error'));

        return redirect()->back();
    }

    /**
     * For editing the existing addresses of current logged in customer.
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $release = $this->customerReleaseRepository->findOneWhere([
            'id'          => $id,
            'customer_id' => $this->customer->id,
        ]);

        if (!$release) {
            abort(404);
        }
        $images=[];
        $language = DB::table('language')->orderBy('name', 'asc')->get();
        $releasetype = DB::table('releaseType')->orderBy('name', 'asc')->get();

        return view($this->_config['view'], array_merge(compact('release','language','releasetype','images'), [
            'defaultCountry' => $release->country,
            'defaultLanguage' =>$release->language,
            'defaultType' =>$release->releasetype,
        ]));
    }

    /**
     * Edit's the premade resource of customer called Release.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $data = $request->all();

        //$data['address1'] = implode(PHP_EOL, array_filter(request()->input('address1')));

        //$releases = $this->customer->releases;
        $releases = DB::table('releases')->get();
        die("1");

        foreach ($releases as $release) {
            if ($id == $release->id) {
                session()->flash('success', trans('shop::app.customer.account.release.edit.success'));

                $this->customerReleaseRepository->update($data, $id);

                return redirect()->route('customer.release.index');
            }
        }

        session()->flash('warning', trans('shop::app.security-warning'));

        return redirect()->route('customer.release.index');
    }

    /**
     * To change the default address or make the default address,
     * by default when first address is created will be the default address.
     *
     * @return \Illuminate\Http\Response
     */
    public function makeDefault($id)
    {
        if ($default = $this->customer->default_release) {
            $this->customerReleaseRepository->find($default->id)->update(['default_release' => 0]);
        }

        if ($release = $this->customerReleaseRepository->find($id)) {
            $release->update(['default_release' => 1]);
        } else {
            session()->flash('success', trans('shop::app.customer.account.release.index.default-delete'));
        }

        return redirect()->back();
    }

    /**
     * Delete address of the current customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $release = $this->customerReleaseRepository->findOneWhere([
            'id'          => $id,
            'customer_id' => $this->customer->id,
        ]);

        if (! $release) {
            abort(404);
        }

        $this->customerReleaseRepository->delete($id);

        session()->flash('success', trans('shop::app.customer.account.release.delete.success'));

        return redirect()->route('customer.release.index');
    }
}
