<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\GenericElement;
use phpDoxExtension\Parser\PSR19\Utils\GenericParser;

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
        $obj = $this->createElement(GenericElement::class, $buffer) ;

        if (preg_match('@^\s*(?<version>[0-9]+\.[^ ]+)\s*@', $this->payload, $matches)) {
            $obj->addAttribute('since', $matches['version']);
            $obj->setBody(mb_substr($this->payload, mb_strlen($matches[0])));
        }
        else {
            $obj->setBody($this->payload);
        }

        return $obj;
    }
}