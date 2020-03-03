<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\AbstractParser;
use phpDoxExtension\Parser\PSR19\Utils\GenericElement;
use phpDoxExtension\Parser\PSR19\Utils\RegexRegistry;

/**
 * Class for "author" tag
 *
 * Syntax : name [<email>]
 *
 * Email MUST respect RFC-2822 and enclosed by square bracket to be recognized
 *
 * Attributes :
 *  - name : author name
 *  - email : author email (without square bracket)
 *  - url : author email URL (mailto)
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class AuthorParser extends AbstractParser {
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

        $last = end($params);
        if (preg_match(RegexRegistry::EMAIL_RFC2822, $last, $matches) === 1) {
            $element->addAttribute('email', $matches['email']);
            array_pop($params);
        }

        $element->addAttribute('name', implode(' ', $params));
        return $element;
    }
}