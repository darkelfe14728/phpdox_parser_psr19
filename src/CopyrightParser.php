<?php

namespace phpDoxExtension\Parser\PSR19;

use TheSeer\phpDox\DocBlock\GenericElement;
use TheSeer\phpDox\DocBlock\GenericParser;

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
        $obj = $this->buildObject('generic', $buffer);
        $obj->setBody($this->payload);

        if (preg_match('@(?<start>[0-9]{4})(?:-(?<end>[0-9]{4}))?@', $this->payload, $matches)) {
            $obj->setRange($matches[0]);
            $obj->setYear_start($matches['start']);
            $obj->setYear_end(empty($matches['end']) ? $matches['start'] : $matches['end']);
        }

        return $obj;
    }
}