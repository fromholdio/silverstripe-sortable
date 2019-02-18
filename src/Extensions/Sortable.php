<?php

namespace Fromholdio\Sortable\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataList;

class Sortable extends DataExtension
{
    private static $db = [
        'Sort'      =>  'Int'
    ];

    private static $default_sort = 'Sort';

    public function updateCMSFields(FieldList $fields)
    {
        $fields->removeByName('Sort');
    }

    public function onBeforeWrite()
    {
        if (!$this->getOwner()->Sort) {
            if ($this->getOwner()->hasMethod('getSortableScope')) {
                $scope = $this->getOwner()->getSortableScope();
                if (is_a($scope, DataList::class)) {
                    $this->getOwner()->Sort = $scope->max('Sort') + 1;
                }
            }
        }
    }
}
