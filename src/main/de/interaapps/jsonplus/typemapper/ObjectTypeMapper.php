<?php
namespace de\interaapps\jsonplus\typemapper;


use de\interaapps\jsonplus\attributes\Serialize;
use de\interaapps\jsonplus\JSONPlus;
use ReflectionClass;

class ObjectTypeMapper implements TypeMapper {
    public function __construct(
        private JSONPlus $jsonPlus
    ){
    }

    public function map(mixed $o, string $type): mixed {
        $class = new ReflectionClass($type);
        $oo = $class->newInstance();

        foreach ($class->getProperties() as $property) {
            if (!$property->isStatic()) {
                $name = $property?->getName();
                $serializeAttribs = $property->getAttributes(Serialize::class);
                foreach ($serializeAttribs as $attrib) {
                    $attrib = $attrib->newInstance();
                    $name = $attrib->value;
                    if ($attrib->hidden)
                        continue 2;
                }

                if ($o != null && isset($o->{$name}))
                    $property->setValue($oo, $this->jsonPlus->map($o?->{$name}, strval($property->getType())));
            }
        }

        return $oo;
    }

    public function mapToJson(mixed $o, string $type): mixed {
        $class = new ReflectionClass($type);
        $oo = [];
        foreach ($class->getProperties() as $property) {
            if (!$property->isStatic()) {
                $name = $property?->getName();
                $overrideName = $property?->getName();
                $serializeAttribs = $property->getAttributes(Serialize::class);
                foreach ($serializeAttribs as $attrib) {
                    $attrib = $attrib->newInstance();
                    $overrideName = $attrib->value;
                    if ($attrib->hidden)
                        continue 2;
                }

                if ($o != null && isset($o->{$name}))
                    $oo[$overrideName] = $o?->{$name};
            }
        }
        return (object) $oo;
    }
}