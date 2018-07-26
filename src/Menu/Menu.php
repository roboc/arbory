<?php

namespace Arbory\Base\Menu;

use Arbory\Base\Html\Elements;
use Arbory\Base\Html\Html;
use Illuminate\Support\Collection;

class Menu
{
    const COOKIE_NAME_MENU = 'menu';

    /**
     * @var Collection
     */
    protected $items;

    /**
     * @param Collection|null $items
     */
    public function __construct( Collection $items = null )
    {
        $this->items = $items ?: new Collection();
    }

    /**
     * @param AbstractItem $item
     * @return void
     */
    public function addItem( AbstractItem $item )
    {
        $this->items->push( $item );
    }

    /**
     * @return Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return Elements\Element
     */
    public function render()
    {
        $list = Html::ul()->addClass( 'nav' );

        foreach( $this->getItems() as $item )
        {
            $name = snake_case( $item->getTitle() );
            $collapsed = $this->getMenuItemCookie( $name );

            if( !$this->hasMenuItemCookie( $name ) )
            {
                $collapsed = true;
            }

            /** @var AbstractItem $item */
            if( !$item )
            {
                continue;
            }

            $li = Html::li()
                ->addClass( 'nav-item' )
                ->addAttributes( [ 'data-name' => $name ] );

            if ( $item->isAccessible() )
            {
                $list->append(
                    $item->render( $li )->addClass( $collapsed ? 'collapsed' : '' )
                );
            }
        }

        return $list;
    }

    /**
     * @return array
     */
    protected function getMenuCookie()
    {
        return (array) json_decode( array_get( $_COOKIE, self::COOKIE_NAME_MENU ) );
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function hasMenuItemCookie( string $name )
    {
        return array_has( $this->getMenuCookie(), $name );
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function getMenuItemCookie( string $name )
    {
        return array_get( $this->getMenuCookie(), $name );
    }
}
