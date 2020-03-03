<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\AbstractParser;
use phpDoxExtension\Parser\PSR19\Utils\GenericElement;
use phpDoxExtension\Parser\PSR19\Utils\RegexRegistry;

/**
 * Class for link tag
 *
 * Syntax : URI [description]
 *
 * URI MUST respect RFC-2396 to be detected
 *
 * Attributes :
 *  - uri : URI
 *
 * Body : description
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class LinkParser extends AbstractParser {

    /**
     * @inheritDoc
     */
    public function allowedAsInline (): bool {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function parse (): GenericElement {
        $element = $this->createElement(GenericElement::class, false);
        $params = $this->getPayloadSplitted();

        if (!empty($params[0]) && preg_match(RegexRegistry::URI_RFC2396, $params[0], $matches) === 1) {
            $element->addAttribute('uri',$params[0]);
            array_shift($params);
        }

        $element->addChild(implode(' ', $params));
        return $element;
    }
}