<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\FQCNParser;
use phpDoxExtension\Parser\PSR19\Utils\GenericElement;
use phpDoxExtension\Parser\PSR19\Utils\RegexRegistry;

/**
 * Class for "uses" tag
 *
 * Syntax : file|FQSEN [description]
 *
 * Attributes [file] :
 *  - file
 *
 * Attributes [FQSEN] :
 *  - class : FQCN
 *  - element : element name (with parenthesis, $, etc.)
 *  - elementType : nature of element (one of NATURE_* constant)
 *
 * Body : description
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class UsesParser extends FQCNParser {
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

        $params = $this->getPayloadSplitted();
        if (!empty($params[0])) {
            $match_FQCN = self::parseFQCN($element, $params);
            if (!$match_FQCN && preg_match(RegexRegistry::URI_RFC2396, $params[0], $match) === 1) {
                $element->addAttribute('file', $params[0]);
                array_shift($params);
            }
        }

        $element->addChild(implode(' ', $params));
        return $element;
    }
}