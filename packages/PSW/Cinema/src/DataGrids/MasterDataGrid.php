<?php

namespace PSW\Cinema\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class MasterDataGrid extends DataGrid
{
    /**
     * Index.
     *
     * @var string
     */
    public $index = 'master_id';

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
        $queryBuilder = DB::table('master as ca')
            ->leftJoin('master_relazioni','ca.master_id', '=', 'master_relazioni.master_id') // PWS#7-2
            ->leftJoin('generi', function($join){
              $join->on('master_relazioni.elemento_id', '=', 'generi.id');
              $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.generi')));
            })  // PWS#7-2
            ->leftJoin('sottogeneri', function($join){
              $join->on('master_relazioni.elemento_id', '=', 'sottogeneri.id');
              $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.sottogeneri')));
            })  // PWS#7-2
            ->leftJoin('countries', function($join){
              $join->on('master_relazioni.elemento_id', '=', 'countries.id');
              $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.countries'))); // PWS#10-20221223
            })  // PWS#7-2
            ->leftJoin('registi', function($join){
              $join->on('master_relazioni.elemento_id', '=', 'registi.id');
              $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.registi')));
            })  // PWS#7-2
            ->leftJoin('sceneggiatori', function($join){
              $join->on('master_relazioni.elemento_id', '=', 'sceneggiatori.id');
              $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.sceneggiatori')));
            })  // PWS#7-2
            ->leftJoin('compositori', function($join){
              $join->on('master_relazioni.elemento_id', '=', 'compositori.id');
              $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.compositori')));
            })  // PWS#7-2
            ->leftJoin('casaproduzione', function($join){
              $join->on('master_relazioni.elemento_id', '=', 'casaproduzione.id');
              $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.casaproduzione')));
            })  // PWS#7-2
            ->leftJoin('language', function($join){
              $join->on('master_relazioni.elemento_id', '=', 'language.id');
              $join->on('master_relazioni.tipo_relazione', '=', DB::raw(config('constants.master.relazioni.lingua')));
            })  // PWS#10-lang
            ->addSelect('ca.url_key',
              DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'compositori.compo_nome_cognome SEPARATOR \', \') as compositori_name'),
              DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'casaproduzione.casa_nome SEPARATOR \', \') as casaproduzione_nome'),'ca.master_id as master_id', 'ca.master_maintitle', 'ca.master_othertitle',
              DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'countries.name SEPARATOR \', \') as country'), 'ca.master_genres',
              DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'generi.generi_name SEPARATOR \', \') as genres_name'),'ca.master_subgenres', DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'sottogeneri.subge_name SEPARATOR \', \') as subgenres_name'), 'ca.master_director',
              DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'registi.registi_nome_cognome SEPARATOR \', \') as director_name'), 'ca.master_year', 'ca.master_actors',
              DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'sceneggiatori.scene_nome_cognome SEPARATOR \', \') as sceneggiatori_name'), 'ca.master_musiccomposers',
              DB::raw('GROUP_CONCAT(DISTINCT ' . DB::getTablePrefix() . 'language.name SEPARATOR \', \') as language_name'),
              'ca.master_type', 'ca.master_vt18', 'ca.master_is_visible', 'ca.master_studios'
            ); // PWS#7 | PWS#10-lang

        $queryBuilder->groupBy('ca.master_id'); // PWS#7
        //     ->addSelect(DB::raw(DB::getTablePrefix() . 'country_states.default_name as state_name'));

        $this->addFilter('master_maintitle', 'ca.master_maintitle');
        $this->addFilter('url_key', 'ca.url_key');
        $this->addFilter('master_othertitle', 'ca.master_othertitle');
        $this->addFilter('master_genres', 'ca.master_genres');
        $this->addFilter('genres_name', DB::raw(DB::getTablePrefix() . 'generi.generi_name'));
        $this->addFilter('master_subgenres', 'ca.master_subgenres');
        $this->addFilter('subgenres_name', DB::raw(DB::getTablePrefix() . 'sottogeneri.subge_name'));
        // $this->addFilter('country', 'ca.master_country'); // PWS#7
        $this->addFilter('country', 'countries.name'); // PWS#7
        $this->addFilter('master_director', 'ca.master_director');
        $this->addFilter('director_name', DB::raw(DB::getTablePrefix() . 'registi.registi_nome_cognome'));
        $this->addFilter('master_year', 'ca.master_year');
        $this->addFilter('master_actors', 'ca.master_actors');
        $this->addFilter('sceneggiatori_name', 'sceneggiatori.scene_nome_cognome'); // PWS#7
        // $this->addFilter('sceneggiatori_name', DB::raw(DB::getTablePrefix() . 'sceneggiatori.scene_nome_cognome')); // PWS#7
        // $this->addFilter('master_musiccomposers', 'ca.master_musiccomposers'); // PWS#7
        $this->addFilter('compositori_name', DB::raw(DB::getTablePrefix() . 'compositori.compo_nome_cognome'));
        $this->addFilter('master_language', 'ca.master_language');
        $this->addFilter('language_name', DB::raw(DB::getTablePrefix() . 'language.name'));
        $this->addFilter('master_type', 'ca.master_type');
        $this->addFilter('master_vt18', 'ca.master_vt18');
        $this->addFilter('master_is_visible', 'ca.master_is_visible');
        $this->addFilter('master_studios', 'ca.master_studios');
        $this->addFilter('casaproduzione_nome', DB::raw(DB::getTablePrefix() . 'casaproduzione.casa_nome'));

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
            'index'      => 'master_id',
            'label'      => trans('admin::app.cinema.master.id'),
            'type'       => 'number',
            'searchable' => false, // PWS#7
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'master_maintitle',
            'label'      => trans('admin::app.cinema.master.master_maintitle'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                if (! empty($row->url_key)) {
                    return "<a href='" . url('masters/'.$row->master_id.'/'.$row->url_key) . "' target='_blank'>" . $row->master_maintitle . "</a>";
                }

                return $row->master_maintitle;
            },
        ]);

        $this->addColumn([
            'index'      => 'master_othertitle',
            'label'      => trans('admin::app.cinema.master.master_othertitle'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'genres_name',
            'label'      => trans('admin::app.cinema.master.master_genres'),
            'type'       => 'int',
            'searchable' => true, // PWS#7
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'subgenres_name',
            'label'      => trans('admin::app.cinema.master.master_subgenres'),
            'type'       => 'string',
            'searchable' => true, // PWS#7
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'master_year',
            'label'      => trans('admin::app.cinema.master.master_year'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'country',
            'label'      => trans('admin::app.cinema.master.master_country'),
            'type'       => 'string',
            'searchable' => true,  // PWS#7
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'director_name',
            'label'      => trans('admin::app.cinema.master.master_director'),
            'type'       => 'string',
            'searchable' => true,  // PWS#7
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'sceneggiatori_name',
            'label'      => trans('admin::app.cinema.master.master_scriptwriters'),
            'type'       => 'string',
            'searchable' => true, // PWS#7
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'compositori_name',
            'label'      => trans('admin::app.cinema.master.master_musiccomposers'),
            'type'       => 'string',
            'searchable' => true, // PWS#7
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'casaproduzione_nome',
            'label'      => trans('admin::app.cinema.master.master_studios'),
            'type'       => 'string',
            'searchable' => true, // PWS#7
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'language_name',
            'label'      => trans('admin::app.cinema.master.master_language'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'master_type',
            'label'      => trans('admin::app.cinema.master.master_type'),
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'master_vt18',
            'label'      => trans('admin::app.cinema.master.master_vt18'),
            'type'       => 'boolean',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'master_is_visible',
            'label'      => trans('admin::app.cinema.master.master_is_visible'),
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
            'route'  => 'admin.cinema.master.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        // $this->addAction([
        //     'title'        => trans('admin::app.datagrid.delete'),
        //     'method'       => 'POST',
        //     'route'        => 'admin.cinema.master.delete',
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
        //     'label'  => trans('admin::app.cinema.master.delete'),
        //     'action' => route('admin.customer.master.massdelete', request('id')),
        //     'method' => 'POST',
        // ]);
    }
}
