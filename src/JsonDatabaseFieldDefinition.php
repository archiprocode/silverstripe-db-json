<?php

namespace ArchiPro\Silverstripe\DbJson;

/**
 * Apply this trait to your Database Schema manager so it knowns how to define json fields.
 */
trait JsonDatabaseFieldDefinition
{
    public function json($values)
    {
        $definition = "json {$values['null']}";
        return $definition;
    }
}
