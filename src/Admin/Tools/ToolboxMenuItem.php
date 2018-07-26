<?php

namespace Arbory\Base\Admin\Tools;

use Arbory\Base\Html\Html;

/**
 * Class AbstractAction
 * @package Arbory\Base\Admin\Form\Fields\ToolboxActions
 */
class ToolboxMenuItem
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $classes = [
        'btn'
    ];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Item constructor.
     * @param string $title
     * @param string $url
     */
    public function __construct( $title, $url )
    {
        $this->setTitle( $title );
        $this->setUrl( $url );
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->render();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     * @return $this
     */
    public function setTitle( $title )
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl( $url )
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getClases()
    {
        return implode( ' ', $this->classes );
    }

    /**
     * @return ToolboxMenuItem
     */
    public function dialog()
    {
        $this->attributes['data-toggle'] = 'modal';
        $this->attributes['data-target'] = '#arbory-modal';
        $this->attributes['data-remote'] = $this->getUrl();

        return $this;
    }

    /**
     * @return ToolboxMenuItem
     */
    public function danger()
    {
        $this->classes[] = 'danger';

        return $this;
    }

    /**
     * @return \Arbory\Base\Html\Elements\Element
     */
    public function render()
    {
        $this->classes[] = 'btn-secondary';
        $this->attributes['href'] = $this->getUrl();
        $this->attributes['title'] = $this->getTitle();

        return Html::link($this->getTitle())
            ->addClass($this->getClases())
            ->addAttributes($this->attributes);
    }
}
