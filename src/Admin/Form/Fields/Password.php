<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Html\Elements\Inputs\Input;
use Arbory\Base\Html\Html;
use Illuminate\Http\Request;

/**
 * Class Password
 * @package Arbory\Base\Admin\Form\Fields
 */
class Password extends AbstractField
{
    /**
     * @return \Arbory\Base\Html\Elements\Element|string
     */
    public function render()
    {
        $input = new Input;
        $input->setName($this->getNameSpacedName());
        $input->setType('password');
        $input->addClass('text');

        return Html::div([
            $input->getLabel($this->getLabel()),
            $input->addClass('form-control')
        ])
            ->addClass('form-group field type-password');
    }

    /**
     * @param Request $request
     * @return void
     */
    public function beforeModelSave( Request $request )
    {
        $password = $request->input( $this->getNameSpacedName() );
        $hasher = \Sentinel::getUserRepository()->getHasher();

        if( $password )
        {
            $this->getModel()->setAttribute( $this->getName(), $hasher->hash( $password ) );
        }
    }
}

