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
        if (!$this->getOwner()->Sort)
        {
            $scope = $this->getOwner()->hasMethod('getSortableScope')
                ? $this->getOwner()->getSortableScope()
                : get_class($this->getOwner())::get();

            if (is_a($scope, DataList::class) && $scope->count() > 0) {
                if ($this->getOwner()->isInDB()) {
                    $scope = $scope->exclude('ID', $this->getOwner()->ID);
                }
                $this->getOwner()->Sort = $scope->max('Sort') + 1;
            }
            else {
                $this->getOwner()->Sort = 1;
            }
        }
    }
}
