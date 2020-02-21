<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\AbstractParser;
use phpDoxExtension\Parser\PSR19\Utils\GenericElement;

/**
 * Class for "copyright" tag
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
 * Body : copyright description (year range included)
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class CopyrightParser extends AbstractParser {
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

        if (preg_match('@(?<start>[0-9]{4})(?:-(?<end>[0-9]{4}))?@', $this->getPayload(), $matches)) {
            $element->addAttribute('range', $matches[0]);
            $element->addAttribute('yearStart', $matches['start']);
            $element->addAttribute('yearEnd', empty($matches['end']) ? $matches['start'] : $matches['end']);
        }

        return $element;
    }
}