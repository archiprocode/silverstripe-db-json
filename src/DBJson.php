<?php
namespace MaximeRainville\Silverstripe\DbJson;

use SilverStripe\ORM\FieldType\DBComposite;


class DBJson extends DBComposite
{

    public function getValue()
    {
        var_dump($this->value);
        return json_decode($this->value, true);
    }


    public function setValue($value, $record = null, $markChanged = true)
    {
        $value = $this->prepValueForDB($value);


        return parent::setValue(['JSON' => $value], $record, $markChanged);
    }

    public function prepValueForDB($value): string
    {
        return json_encode($value);
    }

    public function scalarValueOnly()
    {
        return false;
    }

    public function compositeDatabaseFields()
    {
        return ['JSON' => DBJsonActual::class];
    }
}
