<?php
namespace MaximeRainville\Silverstripe\DbJson\Tests;


use SilverStripe\Dev\SapphireTest;
use MaximeRainville\Silverstripe\DbJson\Tests\DataObjects\DataObjectWithJsonField;

class DBJsonTest extends SapphireTest
{

    protected static $extra_dataobjects = [
        DataObjectWithJsonField::class,
    ];
    public function testDBJson()
    {
        $obj = new DataObjectWithJsonField();
        $obj->JSON = ['key' => 'value'];
        $obj->write();

        $objFromDatabase = DataObjectWithJsonField::get()->byID($obj->ID);
        var_dump($objFromDatabase->dbObject('JSON')->getValue());
        $data = $objFromDatabase->JSON;
        echo "\n\n\nData: \n\n";
        var_dump($data);
        $this->assertEquals(['key' => 'value'], $data);
    }
}
