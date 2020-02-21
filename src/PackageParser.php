<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\AbstractParser;
use phpDoxExtension\Parser\PSR19\Utils\GenericElement;

/**
 * Class for "package" tag
 *
 * Syntax : package
 *
 * Body : package
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class PackageParser extends AbstractParser {
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
        $element = $this->createElement(GenericElement::class, false);
        $element->addChild($this->payload);
        return $element;
    }
}