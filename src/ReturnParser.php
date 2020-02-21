<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\TypeParser;

class ReturnParser extends TypeParser {
    /**
     * @inheritDoc
     */
    public function allowedAsInline (): bool {
        return false;
    }
}