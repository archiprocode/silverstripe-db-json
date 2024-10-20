<?php
namespace ArchiPro\Silverstripe\DbJson;

use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBField;

/**
 * Allow the creating of native JSON fields.
 */
class DBJson extends DBField
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
