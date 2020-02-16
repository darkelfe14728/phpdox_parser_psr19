<?php

namespace phpDoxExtension\Parser\PSR19;

use TheSeer\phpDox\DocBlock\GenericElement;
use TheSeer\phpDox\DocBlock\GenericParser;

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
        $obj = $this->buildObject('generic', $buffer);
        $obj->setBody($this->payload);

        return $obj;
    }
}