<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\GenericElement;
use phpDoxExtension\Parser\PSR19\Utils\TypeElement;

/**
 * Class for "property" tag
 *
 * Syntax: [type] $name [description]
 *
 * Attributes:
 *  - type
 *  - name (with initial $)
 *
 * Body:
 *  - types details
 *  - description
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class PropertyParser extends ParamParser {
    /**
     * @inheritDoc
     */
    protected function parse (): GenericElement {
        $payload = $this->getPayloadSplitted();
        if (preg_match('@^-(?<access>read|write)$@', $payload[0],$match)) {
            $access = $match['access'];
            unset($payload[0]);
        }
        else {
            $access = 'read-write';
        }
        $this->payload = implode(' ', $payload);

        /** @var TypeElement $element */
        $element = parent::parse();
        $element->addAttribute('access', $access);

        return $element;
    }
}