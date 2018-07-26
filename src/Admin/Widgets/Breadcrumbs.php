<?php

namespace Arbory\Base\Admin\Widgets;

use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

/**
 * Class Breadcrumbs
 * @package Arbory\Base\Admin\Widgets
 */
class Breadcrumbs implements Renderable
{
    /**
     * @var Collection
     */
    protected $items;

    /**
     * Breadcrumbs constructor.
     */
    public function __construct()
    {
        $this->items = new Collection();
        $this->addItem( trans( 'arbory::breadcrumbs.home' ), route( 'admin.dashboard.index' ) );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @param $title
     * @param $url
     * @return Breadcrumbs
     */
    public function addItem( $title, $url )
    {
        $this->items->push( [
            'title' => $title,
            'url' => $url
        ] );

        return $this;
    }

    /**
     * @return \Arbory\Base\Html\Elements\Element
     */
    public function render()
    {
        $list = $this->items->map(function (array $item) {
            return Html::li(
                Html::link($item['title'])
                    ->addAttributes([
                        'href' => $item['url']
                    ])
            )->addClass('breadcrumb-item');
        });

        return Html::ol($list->toArray())->addClass('breadcrumb');
    }
}
