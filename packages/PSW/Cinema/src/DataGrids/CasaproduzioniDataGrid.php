<?php

namespace PSW\Cinema\DataGrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class CasaproduzioniDataGrid extends DataGrid
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

        $queryBuilder = DB::table('casaproduzione')
        ->select('casaproduzione.id', 'casaproduzione.casa_nome', 'casaproduzione.status' );

       $this->addFilter('id','id');

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
            'label'      => trans('admin::app.cinema.casaproduzione.id'),
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
        $this->addColumn([
            'index'      => 'casa_nome',
            'label'      => trans('admin::app.cinema.casaproduzione.nome'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        // $this->addColumn([
        //     'index'      => 'compo_cognome',
        //     'label'      => trans('admin::app.cinema.casaproduzione.cognome'),
        //     'type'       => 'string',
        //     'searchable' => true,
        //     'sortable'   => true,
        //     'filterable' => true,
        // ]);
        // $this->addColumn([
        //     'index'      => 'compo_alias',
        //     'label'      => trans('admin::app.cinema.casaproduzione.alias'),
        //     'type'       => 'string',
        //     'searchable' => true,
        //     'sortable'   => true,
        //     'filterable' => true,
        // ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.cinema.casaproduzione.status'),
            'type'       => 'int',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]); 
        // $this->addColumn([
        //     'index'      => 'genere_id',
        //     'label'      => trans('admin::app.cinema.subgenere.genere_id'),
        //     'type'       => 'int',
        //     'searchable' => true,
        //     'sortable'   => true,
        //     'filterable' => true,
        // ]);
        
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
            'route'  => 'admin.cinema.casaproduzione.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        // $this->addAction([
        //     'title'        => trans('admin::app.datagrid.delete'),
        //     'method'       => 'POST',
        //     'route'        => 'admin.cinema.genere.delete',
        //     'confirm_text' => trans('ui::app.datagrid.massaction.delete', ['resource' => 'generi']),
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
        //     'label'  => trans('admin::app.cinema.generi.delete'),
        //     'action' => route('admin.customer.generi.massdelete', request('id')),
        //     'method' => 'POST',
        // ]);
    }
}
