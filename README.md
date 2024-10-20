# Silverstripe CMS Json DBField

MySQL 8 introduced support for native JSON fields. This module adds support for creating and using those fields in Silverstripe CMS.

## Installation

```
composer require maxime-rainville/silverstripe-db-json
```

## Usage

Simply reference the `DBJson` class in your DataObject's `$db` array. This will automatically add a `json` field to the database table.

```php
<?php
use SilverStripe\ORM\DataObject;
use MaximeRainville\Silverstripe\DbJson\DBJson;

class MyDataObject extends DataObject
{
    private static $table_name = 'MyDataObject';

    private static $db = [
        'Payload' => DBJson::class,
    ];
}
```

This will create a table with this definition:

```sql
CREATE TABLE `MyDataObject` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `ClassName` enum('MyDataObject') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'MyDataObject',
  `LastEdited` datetime DEFAULT NULL,
  `Created` datetime DEFAULT NULL,
  `Payload` json NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ClassName` (`ClassName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

## Accessing JSON fields

The module automatically adds accessor and mutator methods for all DBJSON fields.

This allows you to assign array values to your JSON fields. Those values will automatically be encoded as a JSON string on the DataObject's record.

When you read the value back from the DataObject, the JSON string is decoded back into an array.

```php
<?php

$myDataObject = MyDataObject::create();
$myDataObject->Payload = [
    'key' => 'value',
];

$myDataObject->write();

var_dump($myDataObject->Payload);
// array(1) {
//   ["key"]=> string(5) "value"
// }
```

## Filtering results

This module ships with a `Json` filter you can use search your JSON column. Internally the filter will use the [`JSON_CONTAINS` function](https://dev.mysql.com/doc/refman/8.4/en/json-search-functions.html#function_json-contains).

```php
$one = MyDataObject::create();
$myDataObject->Payload = [
    'meta' => [
        'Title' => 'My page',
        'Created' => '2024-01-01',
        'Author' => 'John Doe',
    ]
];
$myDataObject->write();

// ...

// This will return all objects where the `Author` field in the `meta` array is `John Doe`
$list = MyDataObject::get()->filter('Payload:Json', ['$.meta.Author' => 'John Doe']);

// This will return all objects where the `meta` array contains the `Title` and `Created` fields with the provided values
$list = MyDataObject::get()->filter(
    'Payload:Json',
    ['$.meta' => ['Title' => 'My page', 'Created' => '2024-01-01']]
);
```

You can also use the `not` modifier to exclude results.

```php
$list = MyDataObject::get()->filter('Payload:Json:not', ['$.meta.Author' => 'John Doe']);
```

[MySQL 8 provides many native functions to query JSON data](https://dev.mysql.com/doc/refman/8.4/en/json-search-functions.html). You can use the `where` method if you want to use those functions directly.

```php
// The following query will extract the price from the Payload column and only
// return records with a price greater than 4
$list = MyDataObject::get()->where([
    'JSON_EXTRACT("Payload", ?) > ?' => ['$.price', 4],
]);
```

## Gotchas

### Your are using a custom MySQLSchemaManager

Your MySQLSchemaManager must have a `json` method so it it knows how to create `json` columns.

This extension will automatically replace the native MySQLSchemaManager with its own `MySQL8SchemaManager`.

If you've already defined a custom `MySQLSchemaManager`:
- add the `MaximeRainville\Silverstripe\DbJson\JsonDatabaseFieldDefinition` trait to your class.
- Make sure your custom `MySQLSchemaManager` is loaded after the one provided by this extension.

```yml
---
Name: my-custom-db-schema-manager
after:
  name: '#maxime-rainville-silverstripe-db-json'
---
SilverStripe\Core\Injector\Injector:
  MySQLSchemaManager:
    class: App\Project\MyCustomMySQLSchemaManager


