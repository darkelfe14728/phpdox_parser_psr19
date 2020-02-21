<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\AbstractParser;
use phpDoxExtension\Parser\PSR19\Utils\GenericElement;

/**
 * Class for "deprecated" tag
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
class DeprecatedParser extends AbstractParser {
    /**
     * @inheritDoc
     */
    public function allowedAsInline (): bool {
        return false;
    }

    /**
     * @inheritDoc
     */
    protected function parse (): GenericElement {
        $obj = $this->createElement(GenericElement::class, true) ;

        if (preg_match('@^\s*(?<version>[0-9]+\.[^ ]+)\s*@', $this->getPayload(), $matches)) {
            $obj->addAttribute('since', $matches['version']);
            $obj->setBody(mb_substr($this->getPayload(), mb_strlen($matches[0])));
        }
        else {
            $obj->setBody($this->getPayload());
        }

        return $obj;
    }
}