<?php
namespace MaximeRainville\Silverstripe\DbJson\Tests;


use SilverStripe\Dev\SapphireTest;

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
        var_dump($objFromDatabase->dbObject('JSON'));
        $data = $objFromDatabase->JSON;
        $this->assertEquals(['key' => 'value'], $data);
    }
}
