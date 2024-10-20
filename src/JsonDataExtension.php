<?php
namespace ArchiPro\Silverstripe\DbJson;

use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObjectSchema;


/**
 * Automatically add accessor and mutator methods for all DBJSON fields to all DataObjects.
 */
class JsonDataExtension extends DataExtension
{

    /**
     * Register generic mutators and accessors for all DBJSON fields.
     * @return string[]
     */
    public function allMethodNames(): array
    {
        /** @var DataObject $owner */
        $jsonFields = $this->getAllJsonFields();
        $getters = array_map(fn ($field) => 'get' . $field, $jsonFields);
        $setters = array_map(fn ($field) => 'set' . $field, $jsonFields);
        return array_merge($getters, $setters);
    }

    /**
     * Dynamically call accessor or mutator methods for DBJSON fields.
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call(string $method, array $args = [])
    {
        $jsonFields = $this->getAllJsonFields();

        if (str_starts_with($method, 'get')) {
            $field = substr($method, 3);
            return $this->accessor($field);
        } elseif (str_starts_with($method, 'set')) {
            $field = substr($method, 3);
            return $this->mutator($field, $args[0]);
        }
    }

    /**
     * Read the JSON string from the DataObject record and decode it.
     */
    private function accessor(string $field): mixed
    {
        $value = $this->getOwner()->getField($field);
        // Doesn't look like a JSON string, return it as is.
        if (empty($value) || !is_string($value)) {
            return $value;
        }
        return json_decode($value, true);
    }

    /**
     * Encode the value as a JSON string and store it in the DataObject record.
     */
    private function mutator(mixed $field, $value): mixed
    {
        return $this->getOwner()->setField($field, json_encode($value));
    }

    /**
     * Get all the names of the DBJSON fields on the DataObject.
     * @return string[]
     */
    private function getAllJsonFields(): array
    {
        /** @var DataObject $owner */
        $owner = $this->getOwner();
        $fields = DataObjectSchema::create()->databaseFields(get_class($owner));
        $jsonFields = array_filter($fields, fn ($field) => $field === DBJson::class);

        return array_keys($jsonFields);
    }
}
