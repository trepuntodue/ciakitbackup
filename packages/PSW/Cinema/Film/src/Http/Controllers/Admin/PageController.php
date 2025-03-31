<?php
//namespace App\Http\Controllers;
//namespace Webkul\Film\Http\Controllers\Admin;
namespace PSW\Cinema\Film\Http\Controllers\Admin;

use Exception;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use PSW\Cinema\DataGrids\MasterDataGrid;
use PSW\Cinema\Film\Http\Controllers\Admin\Controller;
use PSW\Cinema\Film\Repositories\MastersRepository;
use PSW\Cinema\Film\Facades\MasterImage;
use PSW\Cinema\Film\Repositories\MasterImageRepository;
use PSW\Cinema\DataGrids\MasterDataGrid;
use PSW\Cinema\Film\Models\MasterPage;

//use Webkul\Core\Contracts\Validations\Slug;



class PageController extends Controller
{
    /**
     * To hold the request variables from route file.
     *
     * @var array
     */
    protected $_config;

/**
     * Create a new controller instance.
     *
     * @param  \PSW\Cinema\Film\Repositories\MastersRepository  $masterRepository
     * @param  \PSW\Cinema\Film\Repositories\masterImageRepository  $masterImageRepository
     * @return void
     */
    public function __construct(
        protected mastersRepository $masterRepositor,
        protected masterImageRepository $masterImageRepository,

        )
    {
        $this->_config = request('_config');
    }


    /**
     * Loads the index page showing the static pages resources.
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
     * To create a new Master page.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * To store a new Master page in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //die("FORSE");
        $data = request()->all();

        $this->validate(request(), [
            'url_key'      => ['required', 'unique:cms_page_translations,url_key', new \Webkul\Core\Contracts\Validations\Slug],
            'page_title'   => 'required',
            'channels'     => 'required',
            'html_content' => 'required',
        ]);

        $page = $this->masterRepository->create(request()->all());

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'page']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To edit a previously created CMS page.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $page = $this->masterRepository->findOrFail($id);

        return view($this->_config['view'], compact('page'));
    }

    /**
     * To update the previously created CMS page in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        //"Page Controlloer");
        $locale = core()->getRequestedLocaleCode();

        $this->validate(request(), [
            $locale . '.url_key'      => ['required', new \Webkul\Core\Contracts\Validations\Slug, function ($attribute, $value, $fail) use ($id) {
                if (! $this->masterRepository->isUrlKeyUnique($id, $value)) {
                    $fail(trans('admin::app.response.already-taken', ['name' => 'Page']));
                }
            }],
            $locale . '.page_title'   => 'required',
            $locale . '.html_content' => 'required',
            'channels'                => 'required',
        ]);

        $this->masterRepository->update(request()->all(), $id);


        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Page']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * To delete the previously create CMS page.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $page = $this->masterRepository->findOrFail($id);

        if ($page->delete()) {
            return response()->json(['message' => trans('admin::app.cms.pages.delete-success')]);
        }

        return response()->json(['message' => trans('admin::app.cms.pages.delete-failure')], 500);
    }

    /**
     * To mass delete the CMS resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDelete()
    {
        $data = request()->all();

        if ($data['indexes']) {
            $pageIDs = explode(',', $data['indexes']);

            $count = 0;

            foreach ($pageIDs as $pageId) {
                $page = $this->Repository->find($pageId);

                if ($page) {
                    $page->delete();

                    $count++;
                }
            }

            if (count($pageIDs) == $count) {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', [
                    'resource' => 'CMS Pages',
                ]));
            } else {
                session()->flash('success', trans('admin::app.datagrid.mass-ops.partial-action', [
                    'resource' => 'CMS Pages',
                ]));
            }
        } else {
            session()->flash('warning', trans('admin::app.datagrid.mass-ops.no-resource'));
        }

        return redirect()->route('admin.cms.index');
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
}
