<?php
namespace MaximeRainville\Silverstripe\DbJson;

use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBField;

class DBJsonActual extends DBField
{

    public function requireField()
    {
        $parts = [
            'datatype'   => 'json',
            'null'       => 'not null',
        ];

        $values = ['type' => 'json', 'parts' => $parts];
        DB::require_field($this->tableName, $this->name, $values);
    }
}
