<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\AbstractParser;
use phpDoxExtension\Parser\PSR19\Utils\GenericElement;
use function array_shift;

/**
 * Class for "since" tag
 *
 * Syntax : version [description]
 *
 * Attributes :
 *  - version
 *
 * Body : description
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class SinceParser extends AbstractParser {
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
        $payload = $this->getPayloadSplitted();

        if (count($payload) > 0) {
            $element->addAttribute('version', $payload[0]);
            array_shift($payload);
        }
        $element->addChild(implode(' ', $payload));

        return $element;
    }
}