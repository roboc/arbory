<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class SearchField
 * @package Arbory\Base\Admin\Widgets
 */
class SearchField implements Renderable
{
    /**
     * @var
     */
    protected $action;

    /**
     * @var string
     */
    protected $name;

    /**
     * SearchField constructor.
     * @param $action
     */
    public function __construct( $action )
    {
        $this->action = $action;
        $this->name = 'search';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @param $name
     * @return SearchField
     */
    public function setName( $name )
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $content
     * @return Element
     */
    protected function createForm( $content )
    {
        return Html::form( $content )
            ->addClass( 'search has-text-search' )
            ->addAttributes( [ 'action' => $this->action ] );
    }

    /**
     * @return Element
     */
    public function render()
    {
        $searchInput = Html::input()
            ->setName($this->name)
            ->setType('search')
            ->addClass('form-control')
            ->setValue(request()->get($this->name));

        $submitButton = Html::button(Html::i()->addClass('fa fa-search'))
            ->addClass('btn btn-secondary')
            ->addAttributes([
                'type' => 'submit',
                'title' => trans('arbory::filter.search'),
                'autocomplete' => 'off',
            ]);

        return $this->createForm(
            Html::div([
                $searchInput,
                Html::span($submitButton)->addClass('input-group-append')
            ])->addClass('input-group')
        );
    }
}
