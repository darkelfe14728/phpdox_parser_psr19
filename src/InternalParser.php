<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\GenericElement;
use phpDoxExtension\Parser\PSR19\Utils\GenericParser;

/**
 * Class for internal tag
 *
 * Syntax : [description]
 *
 * Body : description
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class InternalParser extends GenericParser {
    /**
     * @inheritDoc
     */
    public function getObject (array $buffer): GenericElement {
        $obj = $this->createElement(GenericElement::class, $buffer);
        $obj->setBody($this->payload);

        return $obj;
    }
}