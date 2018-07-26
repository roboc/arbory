<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Html\Elements\Element;

/**
 * Class Text
 * @package Arbory\Base\Admin\Form\Fields
 */
class Text extends AbstractField
{
    protected function setup()
    {
        $this->setRenderer(new Renderer\InputFieldRenderer($this));
    }
}
