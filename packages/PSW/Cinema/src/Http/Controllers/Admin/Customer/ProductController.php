<?php

// PWS#prod

namespace PSW\Cinema\Http\Controllers\Admin\Customer;

use PSW\Cinema\DataGrids\ReleaseDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Customer\Repositories\CustomerRepository;
use PSW\Cinema\Customer\Repositories\CustomerReleaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Webkul\Customer\Models\Customer;
use PSW\Cinema\Film\Repositories\ReleaseRepository;
use Illuminate\Http\Request;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Product\Repositories\ProductRepository;


class ProductController extends Controller
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
     * Product Repository instance
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * AttributeFamily Repository instance
     *
     * @var \Webkul\Product\Repositories\AttributeFamilyRepository
     */
    protected $attributeFamilyRepository;

    /**
     * Product Attribute Types
     *
     * @var array
     */
    protected $types;

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
        ProductRepository $productRepository,
        AttributeFamilyRepository $attributeFamilyRepository
    )
    {
        $this->_config = request('_config');
        $this->customer = auth()->guard('customer')->user();

        $this->productRepository = $productRepository;

        $this->attributeFamilyRepository = $attributeFamilyRepository;

        $this->types = [
            'text',
            'textarea',
            'boolean',
            'select',
            'multiselect',
            'datetime',
            'date',
            'price',
            'image',
            'file',
            'checkbox',
        ];
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
      
      $attribute_name = 'user_id';

      $products = DB::table('products as ca')
        ->leftJoin('product_attribute_values as pav', function($join){
          $join->on('ca.id','pav.product_id');
        })
        ->leftJoin('attributes as pa', function($join){
          $join->on('pav.attribute_id', 'pa.id');
        })
        ->where('pa.code',$attribute_name)
        ->where('pav.integer_value',$customer_id)
        ->addSelect( 
          'ca.*',
          DB::raw("
            (
              SELECT pav2.float_value
              FROM " . DB::getTablePrefix() . "product_attribute_values AS pav2
              LEFT JOIN " . DB::getTablePrefix() . "attributes AS pa2 ON pa2.id = pav2.attribute_id
              WHERE pa2.code = 'price' AND pav2.product_id = " . DB::getTablePrefix() . "ca.id
            ) AS price"
          ),
          DB::raw("
            (
              SELECT pav3.text_value
              FROM " . DB::getTablePrefix() . "product_attribute_values AS pav3
              LEFT JOIN " . DB::getTablePrefix() . "attributes AS pa3 ON pa3.id = pav3.attribute_id
              WHERE pa3.code = 'name' AND pav3.product_id = " . DB::getTablePrefix() . "ca.id
            ) AS name"
          ),
        )
        ->orderBy('ca.id', 'desc')
        ->groupBy('ca.id')
        ->get();

        return view($this->_config['view'])->with('products', $products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function create()
    {
      // $customer = $this->customerRepository->find($id);
      
      $customer = Auth::user();

      $customer_id = $this->customer->id;

      $releases = DB::table('releases as ca')
        ->leftJoin('language', function ($join) {
          $join->on('ca.language', '=', 'language.id');
        })
        ->leftJoin('release_images', 'ca.id', '=', 'release_images.release_id')
        ->leftJoin('releaseType as rt', function ($join) {
          $join->on('ca.releasetype', '=', 'rt.id');
        })
        ->leftJoin('release_user', function ($join) use($customer_id) {
          $join->on('ca.id', '=', 'release_user.release_id');
        })
        ->leftJoin('master', 'ca.master_id', '=', 'master.master_id')
        ->leftJoin('master_relazioni', 'ca.master_id', '=', 'master_relazioni.master_id')
        ->leftJoin('generi', function ($join) {
          $join->on('master_relazioni.elemento_id', '=', 'generi.id');
          $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
        })
        ->where('release_user.user_id', '=', DB::raw($customer_id))
        ->where('ca.release_status', '=', DB::raw(1))
        ->addSelect(
            'ca.*',
            'rt.name AS releasetype_name',
            'release_images.path AS path',
            DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'generi.generi_name SEPARATOR \', \') as genres_name'),
            'language.id AS language_id',
            'language.name AS language_label',
            'ca.id AS release_id',
          )
        ->orderBy('ca.id', 'desc')
        ->groupBy('ca.id')
        ->paginate(12);
        
        return view($this->_config['view'], array_merge(compact('customer','releases'), [
            'defaultCountry' =>'',
            'defaultLanguage' =>'',
            'defaultType' =>'',
        ]));
       // return view($this->_config['view'], compact('customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function createFromRelease($release_id)
    {
      $customer = Auth::user();

      $customer_id = $this->customer->id;

      $collection = false;
      $release_user = DB::table('release_user')
        ->where('release_id', '=', DB::raw($release_id))
        ->where('user_id', '=', $customer_id)
        ->get()->first();

      if($release_user) $collection = true;

      if(!$collection){
        DB::table('release_user')->insert(
          array(
          'release_id' => $release_id,
          'user_id'   => $customer_id,
          )
        );
      }
      session()->flash('success', trans('shop::app.customer.account.release.index.aggiunto_a_collezione'));

      return view($this->_config['view'], array_merge(compact('customer','release_id'), [
        'defaultCountry' =>'',
        'defaultLanguage' =>'',
        'defaultType' =>'',
    ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store($release_id)
    {
      $customer_id = $this->customer->id;

      $data = collect(request()->input())->except('_token')->toArray();

      $sku = $this->generateUniqueSku($data['name']);
      
      $default_data = [
          'type' => 'simple',
          'attribute_family_id' => 1,
          'sku' => $sku
      ];

      $product = $this->productRepository->create($default_data);

      $data['url_key'] = $sku;
      $data['user_id'] = $customer_id;
      $data['release_id'] = $release_id;

      // Get core Channel
      $channel = core()->getCurrentChannel();

      $data['locale'] = core()->getCurrentLocale()->code;


      // if ($brand = Attribute::where(['code' => 'brand'])->first()) {
      //     $data['brand'] = AttributeOption::where(['attribute_id' => $brand->id])->first()->id ?? '';
      // }

      $data['channel'] = $channel->code;

      $data['channels'] = [
          0 => $channel->id,
      ];

      $inventorySource = $channel->inventory_sources[0];

      $data['inventories'] = [
          $inventorySource->id => 1,
      ];

      $data['categories'] = [
          0 => $channel->root_category->id,
      ];

      $updated = $this->productRepository->update($data, $product->id);

      session()->flash('success', trans('shop::app.customer.account.product.create.success-create')); // PWS#prod-2

      return redirect()->route('customer.product.index');   
    }

    public function generateUniqueSku($name, $iteration = 0){
      $sku = strlen($name) < 3 ? time() : Str::slug($name);
      $sku = $iteration ? "{$sku}-{$iteration}" : $sku;
      $exists = DB::table('products as ca')
        ->where('ca.sku', $sku)
        ->addSelect('ca.sku')
        ->get()->first();

      if($exists){
        return $this->generateUniqueSku($name, $iteration+1);
      } else{
        return $sku;
      }
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
       $masters = DB::table('master')->orderBy('master_maintitle', 'asc')->get();
       $master_selezionato = (int) DB::table('releases AS r')
         ->where('r.id', $id)
         ->addSelect('r.master_id')
         ->get()
         ->first()->master_id;

        $releasetype = DB::table('releaseType')->orderBy('name', 'asc')->get();
        return view($this->_config['view'], array_merge(compact('release','language','releasetype','masters','master_selezionato'), [
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
        ]);

        $data = collect(request()->input())->except('_token')->toArray();

        $release = $this->customerReleaseRepository->find($id);

        if ($release) {
            $this->customerReleaseRepository->update($data, $id);

            session()->flash('success', trans('admin::app.customers.releases.success-update'));

            // return redirect()->route('admin.customer.releases.index', ['id' => $release->customer_id]);
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
        // $this->customerReleaseRepository->delete($id);

        // session()->flash('success', trans('admin::app.customers.releases.success-delete'));

        // return redirect()->route('customer.release.index', ['id' => $this->customer->id]);

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
        // $Ids = explode(',', request()->input('indexes'));

        // foreach ($Ids as $releaseId) {
        //     $this->customerReleaseRepository->delete($releaseId);
        // }

        // session()->flash('success', trans('admin::app.customers.releases.success-mass-delete'));

        // return redirect()->route($this->_config['redirect'], ['id' => $id]);
    }
}
