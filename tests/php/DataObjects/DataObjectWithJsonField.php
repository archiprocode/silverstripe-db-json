<?php
namespace MaximeRainville\Silverstripe\DbJson\Tests\DataObjects;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;
use MaximeRainville\Silverstripe\DbJson\DBJson;

class DataObjectWithJsonField extends DataObject implements TestOnly
{
    private static $table_name = 'DataObjectWithJsonField';

    private static $db = [
        'JSON' => DBJson::class,
    ];

    public function requireTable()
    {
        return parent::requireTable();
    }
}
