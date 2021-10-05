<?php
namespace de\interaapps\jsonplus;

use de\interaapps\jsonplus\serializationadapter\impl\JsonSerializationAdapter;
use de\interaapps\jsonplus\serializationadapter\impl\phpjson\PHPJsonSerializationAdapter;
use de\interaapps\jsonplus\serializationadapter\SerializationAdapter;
use de\interaapps\jsonplus\typemapper\ObjectTypeMapper;
use de\interaapps\jsonplus\typemapper\PassThroughTypeMapper;
use de\interaapps\jsonplus\typemapper\StdClassObjectTypeMapper;
use de\interaapps\jsonplus\typemapper\TypeMapper;
use ReflectionClass;

class JSONPlus {
    private bool $prettyPrinting = false;
    private array $typeMapper = [];
    private TypeMapper $defaultTypeMapper;
    private TypeMapper $passThroughTypeMapper;
    public static JSONPlus $default;

    public function __construct(
        private SerializationAdapter $serializationAdapter
    ){
        $this->defaultTypeMapper = new ObjectTypeMapper($this);
        $this->passThroughTypeMapper = new PassThroughTypeMapper();
        $this->typeMapper = [
            "object" => $this->passThroughTypeMapper,
            "string" => $this->passThroughTypeMapper,
            "float" => $this->passThroughTypeMapper,
            "int" => $this->passThroughTypeMapper,
            "double" => $this->passThroughTypeMapper,
            "bool" => $this->passThroughTypeMapper,
            "array" => $this->passThroughTypeMapper,
            "boolean" => $this->passThroughTypeMapper,
            "NULL" => $this->passThroughTypeMapper,
            "stdClass" => new StdClassObjectTypeMapper($this),
        ];
    }

    public function fromJson($json, $type=null){
        return $this->map($this->serializationAdapter->fromJson($json), $type);
    }

    public function map($o, $type = null){
        if ($type == null) {
            $type = gettype($o);

            if ($type == "object")
                $type = get_class($o);
        }

        foreach ($this->typeMapper as $typeName => $typeMapper) {
            if ($type == $typeName)
                return $typeMapper->map($o, $type);
        }
        return $this->defaultTypeMapper->map($o, $type);
    }

    public function toJson($o, $type = null) : string {
        return $this->serializationAdapter->toJson($this->mapToJson($o, $type), $this->prettyPrinting);
    }

    public function mapToJson($o, $type = null){
        if ($type == null) {
            $type = gettype($o);
            if ($type == "object")
                $type = get_class($o);
        }
        foreach ($this->typeMapper as $typeName => $typeMapper) {
            if ($type == $typeName)
                return $typeMapper->mapToJson($o, $type);
        }
        return $this->defaultTypeMapper->mapToJson($o, $type);
    }

    public function getSerializationAdapter(): SerializationAdapter {
        return $this->serializationAdapter;
    }

    public function setPrettyPrinting(bool $prettyPrinting): JSONPlus {
        $this->prettyPrinting = $prettyPrinting;
        return $this;
    }

    public static function createDefault() : JSONPlus {
        return new JSONPlus(function_exists("json_decode") ? new PHPJsonSerializationAdapter() : new JsonSerializationAdapter());
    }
}
JSONPlus::$default = JSONPlus::createDefault();