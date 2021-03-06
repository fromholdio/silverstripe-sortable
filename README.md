# silverstripe-sortable

Adds an extension to make any SilverStripe DataObject sortable.

That is, it does the basic lifting of:

* Adding a `Sort` field of type `DBInt` to your object
* Removing the scaffolded field from CMS fields
* Sets the `$default_sort` of your object to `'Sort'`
* Manages setting a `Sort` value of the maximum + 1 upon first write, within a scope you define

## Requirements

SilverStripe 4

## Installation

`composer require fromholdio/silverstripe-sortable`

## Details

It's all plug-n-play once you apply the extension to your data object - with one exception.

To allow the extension to auto-generate a default value, based on maximum + 1, you need to tell the extension what the scope of the group you're getting that maximum value from is.

So, on the object you are extending/applying `Sortable` to, you must add a `getSortableScope()` method.

This method should return a `DataList` on which sortable will run `->max('Sort')` on. See below for example.


## Usage example

We are applying `Sortable` to a `Widget` data object:

```php
class Widget extends DataObject
{
    // or apply via config.yml
    private static $extensions = [
        Sortable::class
    ];
    
    public function getSortableScope()
    {
        return self::get()->exclude('ID', $this->ID);
    }
}
```

That's it! And it assumes that Widgets will be sorted amongst each other globally.

If Widgets had a one-many relationship with pages, and we want to sort them -per page- then we could do the following instead:

```php
class Widget extends DataObject
{
    private static $has_one = [
        'Page' => \Page::class  // (assuming a has_many on Page for Widgets)
    ];
    
    // or apply via config.yml
    private static $extensions = [
        Sortable::class
    ];
    
    public function getSortableScope()
    {
        return self::get()
            ->filter('PageID', $this->PageID)
            ->exclude('ID', $this->ID);
    }
}
```

Depending on your data model structures, the ability to pass a scope DataList can be pretty useful. Have fun!
