<?php
namespace MaximeRainville\Silverstripe\DbJson\Tests;


use SilverStripe\Dev\SapphireTest;
use MaximeRainville\Silverstripe\DbJson\Tests\DataObjects\DataObjectWithJsonField;
use SilverStripe\ORM\DB;

class DBJsonTest extends SapphireTest
{

    protected function setUp(): void
    {
        parent::setUp();
        foreach(DataObjectWithJsonField::get() as $obj) {
            $obj->delete();
        }
    }

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

    public function testJsonFilter()
    {

        $bob = new DataObjectWithJsonField();
        $bob->JSON = [
            'name' => 'bob',
            'parents' => [
                'mom' => 'Mila',
                'dad' => 'John'
            ]
        ];
        $bob->write();

        $elyse = new DataObjectWithJsonField();
        $elyse->JSON = [
            'name' => 'elyse',
            'parents' => [
                'mom' => 'Jackie',
                'dad' => 'Max'
            ]
        ];
        $elyse->write();

        // Test filter by child
        $list = DataObjectWithJsonField::get()->filter('JSON:Json', ['$.name' => 'elyse']);

        $this->assertCount(1, $list, "Only one object is match with the name 'elyse'");
        $this->assertEquals(
            $elyse->ID,
            $list->first()->ID,
            "Object with name 'elyse' should be returned"
        );

        $list = DataObjectWithJsonField::get()->filter(
            'JSON:Json',
            [
                '$.parents' => [
                    'mom' => 'Jackie',
                    'dad' => 'Max'
                ]
            ]
        );

        $this->assertCount(1, $list, 'Only one object has the provided parent entry');
        $this->assertEquals($elyse->ID, $list->first()->ID);

        $list = DataObjectWithJsonField::get()->filter('JSON:Json:not', ['$.name' => 'elyse']);
        $this->assertCount(1, $list, "Only one object does not have the name 'elyse'");
        $this->assertNotEquals($elyse->ID, $list->first()->ID, "Object named 'elyse' should not be returned");

        $test = new DataObjectWithJsonField();
        $test->JSON = [
            'list' => [1, 2, 3],
        ];
        $test->write();

        $list = DataObjectWithJsonField::get()->filter('JSON:Json', ['$.list' => [1, 3, 2]]);
        $this->assertCount(1, $list, "Only one object has the list [1, 2, 3]");
        $this->assertEquals($test->ID, $list->first()->ID, "Object with list [1, 2, 3] should be returned");
    }
}
