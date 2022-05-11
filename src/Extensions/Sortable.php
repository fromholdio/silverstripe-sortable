<?php

namespace Fromholdio\Sortable\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataList;

/**
 * Assumes sort field is named 'Sort' and so creates the db field,
 * and applies a default_sort from this. You can use $sortable_field_name
 * to set a different sort field, but you will need to create the db field
 * and override the $default_sort.
 */
class Sortable extends DataExtension
{
    private static $db = [
        'Sort'      =>  'Int'
    ];

    private static $default_sort = 'Sort';

    private static $sortable_field_name = 'Sort';

    public function updateCMSFields(FieldList $fields): void
    {
        $fields->removeByName('Sort');
    }

    public function onBeforeWrite(): void
    {
        $fieldName = $this->getOwner()->getSortableFieldName();
        if (!$this->getOwner()->{$fieldName})
        {
            $scope = $this->getOwner()->hasMethod('getSortableScope')
                ? $this->getOwner()->getSortableScope()
                : get_class($this->getOwner())::get();

            if (is_a($scope, DataList::class) && $scope->count() > 0) {
                if ($this->getOwner()->isInDB()) {
                    $scope = $scope->exclude('ID', $this->getOwner()->ID);
                }
                $this->getOwner()->{$fieldName} = ($scope->max($fieldName) + 1);
            }
            else {
                $this->getOwner()->{$fieldName} = 1;
            }
        }
    }

    public function getSortableFieldName(): string
    {
        $fieldName = $this->getOwner()->config()->get('sortable_field_name');
        $this->getOwner()->invokeWithExtensions('updateSortableFieldName', $fieldName);
        return $fieldName;
    }
}
