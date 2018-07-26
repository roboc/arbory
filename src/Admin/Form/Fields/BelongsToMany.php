<?php

namespace Arbory\Base\Admin\Form\Fields;

use Arbory\Base\Admin\Form\Fields\Concerns\HasRelationships;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Http\Request;

/**
 * Class BelongsToMany
 * @package Arbory\Base\Admin\Form\Fields
 */
class BelongsToMany extends AbstractField
{
    use HasRelationships;

    /**
     * @return bool
     */
    public function isSortable(  )
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isSearchable()
    {
        return false;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $relatedModel = $this->getRelatedModel();
        $checkboxes = $this->getRelatedModelOptions( $relatedModel );

        return Html::div( [
            Html::h3( $this->getLabel() )->addClass( 'label-wrap' ),
            Html::div( $checkboxes )->addClass( 'value' )
        ] )->addClass( 'form-group field type-associated-set' );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $relatedModel
     * @return Element[]
     */
    public function getRelatedModelOptions( $relatedModel )
    {
        $checkboxes = [];

        $selectedOptions = $this->getValue()->pluck( $relatedModel->getKeyName() )->all();

        foreach( $relatedModel::all() as $modelOption )
        {
            $name = [
                $this->getNameSpacedName(),
                $modelOption->getKey()
            ];

            $checkbox = Html::checkbox()
                ->setName(implode('.', $name))
                ->setValue(1)
                ->addClass('form-check-input');

            if( \in_array( $modelOption->getKey(), $selectedOptions, true ) )
            {
                $checkbox->select();
            }

            $checkboxes[] = Html::div( [
                $checkbox,
                $checkbox->getLabel( (string) $modelOption )->addClass('form-check-label')
            ] )->addClass( 'form-group form-check' );
        }

        return $checkboxes;
    }

    /**
     * @param Request $request
     */
    public function beforeModelSave( Request $request )
    {

    }

    /**
     * @param Request $request
     */
    public function afterModelSave( Request $request )
    {
        $relation = $this->getRelation();

        $submittedIds = $request->input( $this->getNameSpacedName(), [] );
        $existingIds = $this->getModel()->getAttribute( $this->getName() )
            ->pluck( $this->getRelatedModel()->getKeyName() )
            ->toArray();

        foreach( $existingIds as $id )
        {
            if( !array_key_exists( $id, $submittedIds ) )
            {
                $relation->detach( $id );
            }
        }

        foreach( array_keys( $submittedIds ) as $id )
        {
            if( !in_array( $id, $existingIds, true ) )
            {
                $relation->attach( $id );
            }
        }
    }
}
