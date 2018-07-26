<?php

namespace Arbory\Base\Admin\Form\Fields\Renderer;

use Arbory\Base\Admin\Form\Fields\FieldInterface;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class BaseRenderer
 * @package Arbory\Base\Admin\Form\Fields\Renderer
 */
abstract class BaseRenderer implements Renderable
{
    /**
     * @var FieldInterface
     */
    protected $field;

    /**
     * @var string
     */
    protected $type = 'text';

    /**
     * InputFieldRenderer constructor.
     * @param FieldInterface $field
     */
    public function __construct(FieldInterface $field)
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getFieldType()
    {
        return $this->type;
    }

    /**
     * @return Element
     */
    protected function getLabel()
    {
        return Html::label($this->field->getLabel());
    }

    /**
     * @return Element
     */
    abstract protected function getInput();

    /**
     * @param Element|null $label
     * @param Element|null $input
     * @return Element
     */
    protected function buildField(Element $label = null, Element $input = null)
    {
        $template = Html::div()
            ->addClass('form-group field type-' . $this->getFieldType())
            ->addAttributes([
                'data-name' => $this->field->getName()
            ]);

        if ($label) {
            $template->append($label);
        }

        if ($input) {
            $template->append($input);
        }

        return $template;
    }

    /**
     * @return Element
     */
    public function render()
    {
        return $this->buildField($this->getLabel(), $this->getInput());
    }
}
