<?php

namespace phpDoxExtension\Parser\PSR19\Utils;

class GenericParser extends \TheSeer\phpDox\DocBlock\GenericParser {
    /**
     * @inheritDoc
     */
    protected function lookupType ($types_raw): string {
        $types = explode('|', $types_raw);
        foreach($types as &$type) {
            $type = parent::lookupType($type);
        }
        return implode('|', $types);
    }
}