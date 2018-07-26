<?php

namespace Arbory\Base\Admin\Form;

use Arbory\Base\Admin\Form;
use Arbory\Base\Admin\Widgets\Button;
use Arbory\Base\Admin\Widgets\Link;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Builder
 * @package Arbory\Base\Admin\Form
 */
class Builder implements Renderable
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * Builder constructor.
     * @param Form $form
     */
    public function __construct( Form $form )
    {
        $this->form = $form;
    }

    /**
     * @param $route
     * @param array $parameters
     * @return string
     */
    public function url( $route, $parameters = [] )
    {
        return $this->form->getModule()->url( $route, $parameters );
    }

    /**
     * @return \Arbory\Base\Html\Elements\Element
     */
    protected function header()
    {
        return Html::header($this->form->getTitle());
    }

    /**
     * @return Element
     */
    protected function footer()
    {
        $primary = Html::div()->addClass( 'col-md-6 text-right' );
        $secondary = Html::div()->addClass( 'col-md-6' );

        $primary->append(
            Button::create( 'save_and_return', true )
                ->type( 'submit', 'secondary' )
                ->withIcon( 'check' )
                ->disableOnSubmit()
                ->title( trans( 'arbory::resources.save_and_return' ) )
        );

        $primary->append(
            Button::create( 'save', true )
                ->type( 'submit', 'primary' )
                ->withIcon( 'check' )
                ->disableOnSubmit()
                ->title( trans( 'arbory::resources.save' ) )
        );

        $secondary->append(
            Link::create( $this->url( 'index' ) )
                ->asButton( 'secondary' )
                ->withIcon( 'caret-left' )
                ->title( trans( 'arbory::resources.back_to_list' ) )
        );

        $footerTools = Html::div( [
            $secondary,
            $primary,
        ] )->addClass( 'row' );

        return Html::div( $footerTools )->addClass( 'container' );
    }

    /**
     * @param $content
     * @return Element
     */
    protected function form( $content )
    {
        $form = Html::form()->addAttributes( [
            'id' => 'edit-resource',
            'class' => 'edit-resource',
            'novalidate' => 'novalidate',
            'enctype' => 'multipart/form-data',
            'accept-charset' => 'UTF-8',
            'method' => 'post',
            'action' => $this->form->getAction(),
            'data-remote' => 'true',
            'data-remote-validation' => 'true',
            'data-type' => 'json',
        ] );

        $form->append( csrf_field() );

        if( $this->form->getModel()->getKey() )
        {
            $form->append( Html::input()->setName( '_method' )->setType( 'hidden' )->setValue( 'PUT' ) );
        }

        $form->append($content);

        return $form;
    }

    public function render()
    {
        $content = Html::div()->addClass( 'body' );

        foreach( $this->form->fields() as $field )
        {
            $content->append( $field->render() );
        }

        return $this->form(
            Html::div([
                Html::div($this->header())->addClass('card-header'),
                Html::div($content)->addClass('card-body'),
                Html::div($this->footer())->addClass('card-footer'),
            ])->addClass('card')
        );
    }

}
