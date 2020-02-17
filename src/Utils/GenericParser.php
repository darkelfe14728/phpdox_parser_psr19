<?php

namespace phpDoxExtension\Parser\PSR19\Utils;

/**
 * A generic parser
 *
 * @package phpDoxExtension\Parser\PSR19\Utils
 */
class GenericParser extends \TheSeer\phpDox\DocBlock\GenericParser {
    /**
     * Function to create element
     *
     * To used instead of {@see GenericParser::buildObject()}
     *
     * @param string $class The element class
     * @param array  $buffer
     *
     * @return GenericElement The new element
     */
    protected function createElement (string $class, array $buffer): GenericElement {
        /** @var GenericElement $obj */$obj = new $class($this->factory, $this->name);

        if (count($buffer)) {
            $obj->setBody(trim(implode("\n", $buffer)));
        }

        return $obj;
    }

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