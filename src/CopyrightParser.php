<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\GenericElement;
use phpDoxExtension\Parser\PSR19\Utils\GenericParser;

/**
 * Class for copyright tag
 *
 * Syntax : description
 *
 * "description" is recommended to start with year range
 *
 * Attributes:
 *  - range : year range (with "-" as separator)
 *  - year_start : start year of copyright
 *  - year_end : end year of copyright
 *
 * Body : copyright description
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class CopyrightParser extends GenericParser {
    /**
     * @inheritDoc
     */
    public function getObject (array $buffer): GenericElement {
        $obj = $this->createElement(GenericElement::class, $buffer);
        $obj->setBody($this->payload);

        if (preg_match('@(?<start>[0-9]{4})(?:-(?<end>[0-9]{4}))?@', $this->payload, $matches)) {
            $obj->addAttribute('range', $matches[0]);
            $obj->addAttribute('yearStart', $matches['start']);
            $obj->addAttribute('yearEnd', empty($matches['end']) ? $matches['start'] : $matches['end']);
        }

        return $obj;
    }
}