<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\AbstractParser;
use phpDoxExtension\Parser\PSR19\Utils\GenericElement;

/**
 * Class for "see" tag
 *
 * According to PSR, "see" tag can't be inline. Allowed here because make more sense to me ("link" to doesn't allow FQSEN)
 *
 * Syntax : URI|FQSEN [description]
 *
 * Attributes [URI] :
 *  - uri : URI
 *
 * Attributes [FQSEN] :
 *  - class : FQCN
 *  - element : element name (with parenthesis, $, etc.)
 *  - method : function name (without parenthesis)
 *  - property : property name (with $)
 *
 * Body : description
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class SeeParser extends AbstractParser {
    /**
     * @var string A Fully Qualified Structural Element Name (FQSEN) regular expression
     */
    public const REGEX_FQSEN = /** @lang PhpRegExp */
        '@^(?:(?<class>\\\\?(?:[a-zA-Z0-9_]+\\\\)*[a-zA-Z0-9_]+)::)?(?:(?<property>\\$[a-zA-Z0-9_]+)|(?<method>[a-zA-Z0-9_]+\\(\\))|(?<constant>[a-zA-Z0-9_]+))$@';

    /**
     * @var string Type attribute for constants
     */
    public const TYPE_CONSTANT = 'constant';
    /**
     * @var string Type attribute for properties
     */
    public const TYPE_PROPERTY = 'property';
    /**
     * @var string Type attribute for methdos
     */
    public const TYPE_METHOD = 'method';

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

        if (!empty($params[0])) {
            if (preg_match(static::REGEX_FQSEN, $params[0], $match) === 1) {
                $element->addAttribute('class', $match['class']);

                if(!empty($match['property'])) {
                    $element->addAttribute('element', $match['property']);
                    $element->addAttribute('type', self::TYPE_PROPERTY);
                }
                elseif(!empty($match['method'])) {
                    $element->addAttribute('element', $match['method']);
                    $element->addAttribute('type', self::TYPE_METHOD);
                }
                elseif(!empty($match['constant'])) {
                    $element->addAttribute('element', $match['constant']);
                    $element->addAttribute('type', self::TYPE_CONSTANT);
                }

                array_shift($params);
            }
            elseif (preg_match(LinkParser::REGEX_URI_RFC2396, $params[0], $match) === 1) {
                $element->addAttribute('uri', $params[0]);
                array_shift($params);
            }
        }

        $element->addChild(implode(' ', $params));
        return $element;
    }
}