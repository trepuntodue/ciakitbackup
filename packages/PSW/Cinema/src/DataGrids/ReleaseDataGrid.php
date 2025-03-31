<?php

namespace PSW\Cinema\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class ReleaseDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    public $index = 'id';

    /**
     * Sort order.
     *
     * @var string
     */
    protected $sortOrder = 'desc';

    /**
     * Create a new datagrid instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Prepare query builder.
     *
     * @return void
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('releases as ca')
            ->leftJoin('countries', 'ca.country', '=', 'countries.id')
            ->leftJoin('master', 'ca.master_id', '=', 'master.master_id')
            ->leftJoin('language', 'ca.language', '=', 'language.id')
            ->leftJoin('releaseType as rt', 'ca.releasetype', '=', 'rt.id') // PWS#02-23
            ->leftJoin('master AS m', 'ca.master_id', '=', 'm.master_id') // PWS#8
            // ->leftJoin('generi', 'ca.master_genres', '=', 'generi.id')
            // ->leftJoin('sottogeneri', 'ca.master_subgenres', '=', 'sottogeneri.id')
            // ->leftJoin('registi', 'ca.master_director', '=', 'registi.id')
            // ->leftJoin('sceneggiatori', 'ca.master_scriptwriters', '=', 'sceneggiatori.id')
            // ->leftJoin('compositori', 'ca.master_musiccomposers', '=', 'compositori.id')
            // ->leftJoin('casaproduzione', 'ca.master_studios', '=', 'casaproduzione.id')
            ->addSelect('ca.url_key',
            'rt.name AS release_type', // PWS#02-23
            DB::raw("( " .
            "CASE " .
                "WHEN release_vt18 = 1 THEN 'Sì' " .
                "WHEN release_vt18 = 0 THEN 'No' " .
            "END) AS release_vt18"),
            'ca.id', 'ca.customer_id', 'ca.original_title','ca.country',DB::raw('' . DB::getTablePrefix() . 'language.name as language_name'), DB::raw('' . DB::getTablePrefix() . 'master.url_key as master_url'), DB::raw('' . DB::getTablePrefix() . 'countries.name as country_name'), 'ca.other_title',
            'm.master_maintitle','m.master_id','ca.release_year', 'ca.release_distribution', 'ca.language', 
            DB::raw("( " .
            "CASE " .
                "WHEN release_status = -1 THEN '" . __('admin::app.cinema.release.release_status_pending') . "' " .
                "WHEN release_status = 0 THEN '" . __('admin::app.cinema.release.release_status_rifiutato') . "' " .
                "WHEN release_status = 1 THEN '" . __('admin::app.cinema.release.release_status_approvato') . "' " .
            "END) AS release_status"), 
            'ca.default_release', 'ca.release_featured'); // PWS#8 // PWS#13-status // PWS#releasedatagrid


        // $queryBuilder->groupBy('ca.master_id')
        //     ->addSelect(DB::raw(DB::getTablePrefix() . 'country_states.default_name as state_name'));

        $this->addFilter('id', 'ca.id');
        $this->addFilter('customer_id', 'ca.customer_id');
        $this->addFilter('original_title', 'ca.original_title');
        $this->addFilter('other_title', 'ca.other_title');
        $this->addFilter('country', 'ca.master_country');
        $this->addFilter('country_name', DB::raw(DB::getTablePrefix() . 'countries.name'));
        $this->addFilter('master_id', 'ca.master_id');
        $this->addFilter('master_url', DB::raw(DB::getTablePrefix() . 'master.url_key'));
        $this->addFilter('release_year', 'ca.release_year');
        $this->addFilter('release_type', 'rt.name'); // PWS#02-23
        $this->addFilter('release_distribution', 'ca.release_distribution');
        $this->addFilter('language', 'ca.language');
        $this->addFilter('release_status', 'ca.release_status');
        $this->addFilter('default_release', 'ca.default_release');
        $this->addFilter('release_featured', 'ca.release_featured');
        $this->addFilter('url_key', 'ca.url_key');
        $this->addFilter('release_vt18', 'ca.release_vt18');



       $this->setQueryBuilder($queryBuilder);

    }

    /**
     * Add columns.
     *
     * @return void
     */
    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('admin::app.cinema.release.id'),
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'original_title',
            'label'      => trans('admin::app.cinema.release.original_title'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
            if (! empty($row->url_key)) {
                return "<a href='" . url('releases/'.$row->id.'/'.$row->url_key) . "' target='_blank'>" . $row->original_title . "</a>";
            }
                return $row->original_title;
        }
        ]);

        $this->addColumn([
            'index'      => 'other_title',
            'label'      => trans('admin::app.cinema.release.other_title'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'release_year',
            'label'      => trans('admin::app.cinema.release.release_year'),
            'type'       => 'int',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'release_type', // PWS#02-23
            'label'      => trans('admin::app.cinema.release.releasetype'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'release_distribution',
            'label'      => trans('admin::app.cinema.release.release_distribution'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'country',
            'label'      => trans('admin::app.cinema.release.country'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'language_name',
            'label'      => trans('admin::app.cinema.release.language'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'master_id',
            'label'      => trans('admin::app.cinema.release.master_id'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
            if (! empty($row->master_url)) {
                return "<a href='" . url('masters/'.$row->master_id.'/'.$row->master_url) . "' target='_blank'>" . $row->master_maintitle . "</a>"; // PWS#8
            }
                return $row->master_id;
            }
        ]);
        $this->addColumn([
            'index'      => 'release_featured',
            'label'      => trans('admin::app.cinema.release.release_featured'),
            'type'       => 'boolean',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'release_status',
            'label'      => trans('admin::app.cinema.release.release_status'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'release_vt18',
            'label'      => trans('admin::app.cinema.release.release_vt18'),
            'type'       => 'boolean',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);


    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.cinema.release.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        // $this->addAction([
        //     'title'        => trans('admin::app.datagrid.delete'),
        //     'method'       => 'POST',
        //     'route'        => 'admin.cinema.release.delete',
        //     'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'master']),
        //     'icon'         => 'icon trash-icon',
        // ]);
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        // $this->addMassAction([
        //     'type'   => 'delete',
        //     'label'  => trans('admin::app.cinema.release.delete'),
        //     'action' => route('admin.customer.release.massdelete', request('id')),
        //     'method' => 'POST',
        // ]);
    }
}
