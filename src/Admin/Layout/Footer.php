<?php

namespace Arbory\Base\Admin\Layout;

use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

/**
 * Class Footer
 * @package Arbory\Base\Admin\Layout
 */
class Footer implements Renderable
{
    /**
     * @var string|null
     */
    protected $type;

    /**
     * @var Collection
     */
    protected $rows;

    /**
     * Footer constructor.
     * @param string|null $type
     */
    public function __construct()
    {
        $this->rows = new Collection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @return Collection
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return Element
     */
    public function render()
    {
        $footer = Html::div();

        foreach( $this->getRows() as $row )
        {
            $footer->append( $row->render() );
        }

        return $footer;
    }
}
