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

        $this->assertEquals(
            ['key' => 'value'],
            $obj->JSON,
            'JSON value set on DBJSON field can be retrieved'
        );
        $obj->write();

        $objFromDatabase = DataObjectWithJsonField::get()->byID($obj->ID);
        $this->assertEquals(
            ['key' => 'value'],
            $objFromDatabase->JSON,
            'JSON value set on DBJSON field can be retrieved from database'
        );
    }
}
