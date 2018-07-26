<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Elements\Inputs\Input;
use Arbory\Base\Html\Html;
use Illuminate\Support\Collection;

/**
 * Class AssociatedSetRenderer
 * @package Arbory\Base\Admin\Form\Fields\Renderer
 */
class AssociatedSetRenderer
{
    /**
     * @var FieldInterface
     */
    protected $field;

    /**
     * @var array
     */
    protected $values;

    /**
     * @var Collection
     */
    protected $options;

    /**
     * AssociatedSetRenderer constructor.
     * @param FieldInterface $field
     * @param Collection $options
     */
    public function __construct( FieldInterface $field, Collection $options )
    {
        $this->field = $field;
        $this->values = (array) $field->getValue();
        $this->options = $options;
    }

    /**
     * @return \Arbory\Base\Html\Elements\Element
     */
    protected function getLabel()
    {
        return Html::label( $this->field->getLabel() );
    }

    /**
     * @return Content
     */
    protected function getAssociatedItemsList()
    {
        $content = new Content();

        $index = 0;

        foreach( $this->options as $value => $label )
        {
            $content[] = $this->getAssociatedItem(
                $this->field->getNameSpacedName() . '.' . $index,
                $value,
                $label
            );

            $index++;
        }

        return $content;
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $label
     * @return Element
     */
    protected function getAssociatedItem( $name, $value, $label )
    {
        $checkbox = Html::checkbox()->setName( $name )->setValue( $value );
        $checkbox->append($checkbox->getLabel($label)->addClass('form-check-label'));
        $checkbox->addClass('form-check-input');

        if( in_array( $value, $this->values, true ) )
        {
            $checkbox->select();
        }

        return Html::div( $checkbox )->addClass( 'form-check type-associated-set-item' );
    }

    /**
     * @return Element
     */
    public function render()
    {
        return Html::div([
            Html::h3($this->getLabel()),
            Html::div($this->getAssociatedItemsList())
        ])->addClass('field type-associated-set');
    }
}
