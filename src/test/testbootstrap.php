<?php
// Init

use de\interaapps\jsonplus\attributes\Serialize;
use de\interaapps\jsonplus\JSONModel;
use de\interaapps\jsonplus\JSONPlus;
use de\interaapps\jsonplus\serializationadapter\impl\JsonSerializationAdapter;
use de\interaapps\jsonplus\serializationadapter\impl\phpjson\PHPJsonSerializationAdapter;

chdir(".");;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
(require_once './autoload.php')();

// Testing
echo "Testing:\n";

class Test2 {
    public string $sheesh;
}

class Test {
    use JSONModel;
    #[Serialize("name_")]
    public string $name = "NOT INITIALIZED";
    public bool $test;
    public int $feef;
    public array $aeef;
    public object $aeef2;
    public Test2 $test2;
    public function __construct(){
    }

    public function setName(string $name): Test {
        $this->name = $name;
        return $this;
    }
}

const JSON = '{
    "name_":"Wo\"\nrld!\\\ /",
    "aeef23": {},
    "aeef2": {"test": true},
    "test": false,
    "feef": 21,
    "aeef": ["1","2","3"],
    "test2": "a"
}';

//echo Test::fromJson(JSON)->toJson();
$json = new JSONPlus(new PHPJsonSerializationAdapter());
$var = $json->fromJson(JSON, Test::class);
echo $var->name."\n";
echo $json->toJson($var);
