<?php
namespace ArchiPro\Silverstripe\DbJson;

use SilverStripe\ORM\Connect\MySQLSchemaManager;

/**
 * Apply this trait to your Database Schema manager so it knowns how to define json fields.
 */
trait JsonDatabaseFieldDefinition
{
    public function json($values)
    {
        $definition = "JSON {$values['null']}";
        return $definition;
    }
}
