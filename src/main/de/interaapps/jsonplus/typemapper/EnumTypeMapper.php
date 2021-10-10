<?php

namespace de\interaapps\jsonplus\typemapper;

class EnumTypeMapper implements TypeMapper {

    public function map(mixed $o, string $type): mixed {
        return $type::{$o}->value;
    }

    public function mapToJson(mixed $o, string $type): mixed {
        return $o->name;
    }
}