<?php

namespace CubeSystems\Leaf\Html\Elements\Inputs;

use CubeSystems\Leaf\Html\Elements\Element;
use CubeSystems\Leaf\Html\Html;

abstract class AbstractInputField extends Element
{
    public function setValue( $value )
    {
        $this->attributes()->put( 'value', $value );

        return $this;
    }

    public function setName( $name )
    {
        $this->attributes()->put( 'name', Element::formatName( $name ) );
        $this->attributes()->put( 'id', $this->formatInputId() );

        return $this;
    }

    public function getLabel( $text )
    {
        return Html::label( $text )->addAttributes( [ 'for' => $this->attributes()->get( 'id' ) ] );
    }

    protected function formatInputId()
    {
        return strtr( $this->attributes()->get( 'name' ), [ '[' => '_', ']' => '' ] );
    }
}
