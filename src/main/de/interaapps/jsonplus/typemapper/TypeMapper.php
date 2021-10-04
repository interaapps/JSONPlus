<?php
namespace de\interaapps\jsonplus\typemapper;

interface TypeMapper {
    public function map(mixed $o, string $type) : mixed;
    public function mapToJson(mixed $o, string $type) : mixed;
}