<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\FieldSet;
use Arbory\Base\Admin\Traits\Renderable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class AbstractField
 * @package Arbory\Base\Admin\Form\Fields
 */
abstract class AbstractField implements FieldInterface
{
    use Renderable;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var FieldSet
     */
    protected $fieldSet;

    /**
     * AbstractField constructor.
     * @param string $name
     */
    public function __construct( $name )
    {
        $this->setName( $name );
        $this->setup();
    }

    /**
     * @return void
     */
    protected function setup()
    {

    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName( $name )
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getNameSpacedName()
    {
        return implode( '.', [
            $this->getFieldSet()->getNamespace(),
            $this->getName()
        ] );
    }

    /**
     * @return string
     */
    public function getFieldTypeName()
    {
        return 'type-' . camel_case(class_basename(static::class));
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        if( $this->value === null )
        {
            $this->value = $this->getModel()->getAttribute( $this->getName() );
        }

        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue( $value )
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        if( $this->label === null )
        {
            return $this->name;
        }

        return $this->label;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel( $label )
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->getFieldSet()->getModel();
    }

    /**
     * @return FieldSet
     */
    public function getFieldSet()
    {
        return $this->fieldSet;
    }

    /**
     * @param FieldSet $fieldSet
     * @return $this
     */
    public function setFieldSet( FieldSet $fieldSet )
    {
        $this->fieldSet = $fieldSet;

        return $this;
    }

    /**
     * @param Request $request
     */
    public function beforeModelSave( Request $request )
    {
        $value = $request->has( $this->getNameSpacedName() )
            ? $request->input( $this->getNameSpacedName() )
            : null;

        $this->getModel()->setAttribute( $this->getName(), $value );
    }

    /**
     * @param string $rules
     * @return FieldInterface
     */
    public function rules( string $rules ): FieldInterface
    {
        $this->rules = array_merge( $this->rules, explode( '|', $rules ) );

        return $this;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [ $this->getNameSpacedName() => implode( '|', $this->rules ) ];
    }

    /**
     * @param Request $request
     */
    public function afterModelSave( Request $request )
    {

    }
}
