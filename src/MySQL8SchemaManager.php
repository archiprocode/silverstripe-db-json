<?php
namespace MaximeRainville\Silverstripe\DbJson;

use SilverStripe\ORM\Connect\MySQLSchemaManager;

/**
 *
 */
class MySQL8SchemaManager extends MySQLSchemaManager
{
    public function json($values)
    {
        $definition = "JSON {$values['null']}";
        var_dump($definition);
        return $definition;
    }
}
