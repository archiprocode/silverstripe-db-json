<?php
namespace MaximeRainville\Silverstripe\DbJson;

use SilverStripe\ORM\Connect\MySQLSchemaManager;

/**
 * Extends the default MySQLSchemaManager to add support for JSON fields.
 */
class MySQL8SchemaManager extends MySQLSchemaManager
{
    use JsonDatabaseFieldDefinition;
}
