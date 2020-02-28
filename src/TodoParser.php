<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\AbstractParser;
use phpDoxExtension\Parser\PSR19\Utils\GenericElement;

/**
 * Class for "internal" tag
 *
 * Syntax : [description]
 *
 * Body : description
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class TodoParser extends AbstractParser {
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
        $element = $this->createElement(GenericElement::class, true);
        $element->addChild($this->getPayload());
        return $element;
    }
}