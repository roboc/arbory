<?php

namespace Arbory\Base\Admin\Grid;

use Arbory\Base\Admin\Grid;
use Arbory\Base\Admin\Layout\Footer;
use Arbory\Base\Admin\Layout\Footer\Tools;
use Arbory\Base\Admin\Widgets\Link;
use Arbory\Base\Admin\Widgets\SearchField;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Builder
 * @package Arbory\Base\Admin\Grid
 */
class Builder implements Renderable
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * Builder constructor.
     * @param Grid $grid
     */
    public function __construct( Grid $grid )
    {
        $this->grid = $grid;
    }

    /**
     * @return Grid
     */
    public function grid()
    {
        return $this->grid;
    }

    public function getItems()
    {
        return $this->grid->getItems();
    }

    /**
     * @return \Arbory\Base\Html\Elements\Element
     */
    protected function searchField()
    {
        if( !$this->grid->hasTool( 'search' ) )
        {
            return null;
        }

        return ( new SearchField( $this->url( 'index' ) ) )->render();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getTableColumns()
    {
        $tableColumns = $this->grid()->getColumns()->map( function( Column $column )
        {
            return $this->getColumnHeader( $column );
        } );

        $tableColumns->push(Html::th(' '));

        return $tableColumns;
    }

    /**
     * @param Column $column
     * @return Element
     */
    protected function getColumnHeader( Column $column )
    {
        if( $column->isSortable() )
        {
            $link = Html::link( $column->getLabel() )
                ->addAttributes( [
                    'href' => $this->getColumnOrderUrl( $column->getName() )
                ] );

            if( request( '_order_by' ) === $column->getName() )
            {
                $link->append( $this->getOrderByIcon() );
            }

            return Html::th( $link );
        }

        return Html::th( Html::span( $column->getLabel() ) );
    }

    /**
     * @param $column
     * @return string
     */
    protected function getColumnOrderUrl( $column )
    {
        return $this->grid->getModule()->url( 'index', array_filter( [
            'search' => request( 'search' ),
            '_order_by' => $column,
            '_order' => request( '_order' ) === 'ASC' ? 'DESC' : 'ASC',
        ] ) );
    }

    /**
     * @return Element
     */
    protected function getOrderByIcon()
    {
        return Html::i()
            ->addClass( 'fa' )
            ->addClass(
                ( request( '_order' ) === 'DESC' )
                    ? 'fa-long-arrow-up'
                    : 'fa-long-arrow-down'
            );
    }

    /**
     * @return Element
     */
    protected function header(): Element
    {
        $row = Html::div([
            Html::div(trans('arbory::resources.all_resources'))->addClass('col-md-6'),
            Html::div($this->createButton())->addClass('col-md-6 text-right'),
        ])->addClass('row');

        return Html::div($row)->addClass('card-header bg-light');
    }

    protected function filter(): Element
    {
        return Html::div(
            Html::div(
                $this->searchField()
            )->addClass('col-md-4 offset-md-8')
        )->addClass('row mb-3');
    }

    /**
     * @return Element
     */
    protected function table()
    {
        return Html::table([
            Html::thead(
                Html::tr($this->getTableColumns()->toArray())
            ),
            Html::tbody(
                $this->grid()->getRows()->map(function (Row $row) {
                    return $row->render();
                })->toArray()
            ),
        ])
            ->addAttributes(['id' => 'resources-list'])
            ->addClass('table table-responsive-sm');
    }

    protected function tableFooter()
    {
        $total = $this->grid->isPaginated()
            ?  $this->getItems()->total()
            : count( $this->getItems() );

        return Html::div([
            Html::div(trans( 'arbory::pagination.items_found', [ 'total' => $total ] ))->addClass('col-md-4 text-muted'),
            Html::div($this->pagination())->addClass('col-md-4'),
        ])->addClass('row justify-content-start');
    }

    /**
     * @return \Illuminate\Support\HtmlString|null
     */
    protected function pagination()
    {
        if (!$this->grid->isPaginated() || !$this->getItems()->hasPages()) {
            return null;
        }

        return $this->getItems()->render();
    }

    /**
     * @return Element
     */
    protected function createButton()
    {
        if (!$this->grid->hasTool('create')) {
            return null;
        }

        return Html::link([
            Html::i()->addClass('fa fa-plus'),
            trans('arbory::resources.create_new'),
        ])
            ->addClass('btn btn-sm btn-primary')
            ->addAttributes([
                'href' => $this->url('create')
            ]);
    }

    /**
     * @return Element
     */
    protected function exportOptions()
    {
        $parameters = request()->all();

        $buttons = [
            Html::link('XLS')->addClass('btn btn-secondary')->addAttributes(['href' => $this->url('export', $parameters + ['as' => 'xls']) ]),
            Html::link('JSON')->addClass('btn btn-secondary')->addAttributes(['href' => $this->url('export', $parameters + ['as' => 'json']) ]),
        ];

        return Html::div([
            Html::div(trans('arbory::resources.export'))
                ->addClass('btn-group btn-group-sm'),
            Html::div($buttons)
                ->addClass('btn-group btn-group-sm')
                ->addAttributes([
                    'role' => 'group',
                ]),
        ]);
    }

    /**
     * @return Tools
     */
    protected function footerTools()
    {
        $tools = new Tools();
        $tools->getBlock( 'secondary' )->push( $this->exportOptions() );

        $this->addCustomToolsToFooterToolset( $tools );

        return $tools;
    }

    /**
     * @param Tools $toolset
     * @return void
     */
    protected function addCustomToolsToFooterToolset( Tools $toolset )
    {
        foreach( $this->grid->getTools() as list( $tool, $location ) )
        {
            $toolset->getBlock( $location )->push( $tool->render() );
        }
    }

    /**
     * @return \Arbory\Base\Html\Elements\Element
     */
    protected function footer()
    {
        $footer = new Footer();

        if ($this->grid->hasTools()) {
            $footer->getRows()->prepend($this->footerTools());
        }

        return Html::div($footer->render())->addClass('card-footer bg-light');
    }

    /**
     * @param $route
     * @param array $parameters
     * @return string
     */
    public function url( $route, $parameters = [] )
    {
        return $this->grid()->getModule()->url( $route, $parameters );
    }

    /**
     * @return Element
     */
    public function render()
    {
        return Html::div([
            $this->header(),
            Html::div([
                $this->filter(),
                $this->table(),
                $this->tableFooter(),
            ])->addClass('card-body'),
            $this->footer(),
        ])
            ->addClass('card')
            ->addAttributes(['id' => 'resources-grid']);
    }
}
