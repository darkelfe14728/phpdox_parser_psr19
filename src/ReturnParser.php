<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\TypeParser;

/**
 * Class for "return" tag
 *
 * Syntax: [type] [description]
 *
 * Attributes:
 *  - type
 *
 * Body:
 *  - types details
 *  - description
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class ReturnParser extends TypeParser {
    /**
     * @inheritDoc
     */
    public function allowedAsInline (): bool {
        return false;
    }
}