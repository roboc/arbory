<?php

namespace Arbory\Base\Menu;

use Arbory\Base\Html\Elements;
use Arbory\Base\Html\Html;
use Illuminate\Support\Collection;

class Group extends AbstractItem
{
    /**
     * @var Collection
     */
    protected $children;

    /**
     * Group constructor.
     */
    public function __construct()
    {
        $this->children = new Collection();
    }

    /**
     * @param Elements\Element $parentElement
     * @return Elements\Element
     */
    public function render( Elements\Element $parentElement ): Elements\Element
    {
        $ul = Html::ul()->addClass( 'nav-dropdown-items' );

        foreach( $this->getChildren() as $child )
        {
            /** @var AbstractItem $child */
            $li = Html::li()
                ->addClass('nav-item')
                ->addAttributes( [ 'data-name' => snake_case( $child->getTitle() ) ] );

            if( $child->isAccessible() )
            {
                $child->render( $li );

                if( $child->isActive() )
                {
                    $li->addClass( 'active' );
                }

                $ul->append( $li );
            }
        }

        $parentElement->addClass('nav-dropdown');

        return
            $parentElement
                ->append(
                    Html::link([
                        Html::i()->addClass('nav-icon fa fa-folder-o'),
                        $this->getTitle(),
                    ])->addClass('nav-link nav-dropdown-toggle ' . ($this->isActive() ? 'active' : ''))
                )
                ->append($ul);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) $this->getChildren()->first( function( Item $item )
        {
            return $item->isActive();
        } );
    }

    /**
     * @param AbstractItem $child
     * @return void
     */
    public function addChild( AbstractItem $child )
    {
        $this->children->push( $child );
    }

    /**
     * @return Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @param Collection $children
     */
    public function setChildren( Collection $children )
    {
        $this->children = $children;
    }

    /**
     * @return bool
     */
    public function isAccessible(): bool
    {
        foreach( $this->getChildren() as $item )
        {
            if( $item->isAccessible() )
            {
                return true;
            }
        }

        return false;
    }
}
