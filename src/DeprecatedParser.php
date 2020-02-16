<?php

namespace phpDoxExtension\Parser\PSR19;

use TheSeer\phpDox\DocBlock\GenericElement;
use TheSeer\phpDox\DocBlock\GenericParser;

/**
 * Class for deprecated tag
 *
 * Syntax : [version] [description]
 *
 * Attributes :
 *  - since : the version since the element is deprecated
 *
 * Body : description
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class DeprecatedParser extends GenericParser {
    /**
     * @inheritDoc
     */
    public function getObject (array $buffer): GenericElement {
        $obj = $this->buildObject('generic', $buffer) ;

        if (preg_match('@^\s*(?<version>[0-9]+\.[^ ]+)\s*@', $this->payload, $matches)) {
            $obj->setSince($matches['version']);
            $obj->setBody(mb_substr($this->payload, mb_strlen($matches[0])));
        }
        else {
            $obj->setBody($this->payload);
        }

        return $obj;
    }
}