<?php

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;

class DataObjectWithJsonField extends DataObject implements TestOnly
{
    private static $db = [
        'JSON' => DBJson::class,
    ];

    public function requireTable()
    {
        return parent::requireTable();
    }
}
