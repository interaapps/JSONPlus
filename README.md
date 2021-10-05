# JSONPlus `1.0`

## Getting started

```php
<?php

use de\interaapps\jsonplus\attributes\Serialize;
use de\interaapps\jsonplus\JSONModel;use de\interaapps\jsonplus\JSONPlus;use de\interaapps\jsonplus\serializationadapter\impl\JsonSerializationAdapter;use de\interaapps\jsonplus\serializationadapter\impl\phpjson\PHPJsonSerializationAdapter;

class Test2 {
    use JSONModel;
    
    #[Serialize("my_array")]
    public array $myArray;
}

class Test {
    // Adds the Test#toJson and Test::fromJson functions
    use JSONModel;
    
    public string $test;
    public Test2 $test2;
}

$test = Test::fromJson('{
    "test": "Hello World",
    "test2": {
        "my_array": ["Hello There"]
    }
}');

echo $test->toJson();

// Custom JSONPlus Instance
$jsonPlus = JSONPlus::createDefault();
// $jsonPlus = new JSONPlus(new PHPJsonSerializationAdapter());
$arrJson =  $jsonPlus->toJson([
    "A", "B", "C"
]);
echo $arrJson;
// '["A", "B", "C"]'

echo $jsonPlus->fromJson($arrJson)[0];
// "A"

// Setting JSONModal
Test::setJsonPlusInstance($jsonPlus);
// For all (Default instance)
JSONPlus::$default = $jsonPlus;

/// Using other JSON-Drivers
// Uses PHPs default json_encode and json_decode methods
$jsonPlus = new JSONPlus(new PHPJsonSerializationAdapter());
// Uses an custom implementation of JSON. This will be automatically chosen by JSONPlus::createDefault when json_decode doesn't exist 
$jsonPlus = new JSONPlus(new JsonSerializationAdapter());
```

## Installation
#### UPPM
```
uppm install interaapps/jsonplus
```
#### Composer
```
composer require interaapps/jsonplus
```