<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\GenericElement;
use phpDoxExtension\Parser\PSR19\Utils\RegexRegistry;
use phpDoxExtension\Parser\PSR19\Utils\TypeElement;
use phpDoxExtension\Parser\PSR19\Utils\TypeParser;
use function array_shift;
use function count;

/**
 * Class for "var" tag
 *
 * Syntax: type [element] [description]
 *
 * Constants MUST be full upper case to be detect
 *
 * Attributes:
 *  - type : type of element
 *  - element : element name (with $ for properties)
 *  - nature : nature of element (one of NATURE_* constant)
 *
 * Body:
 *  - types details
 *  - description
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class VarParser extends TypeParser {
    /**
     * @var string Type attribute for constants
     */
    public const NATURE_CONSTANT = 'constant';
    /**
     * @var string Type attribute for properties
     */
    public const NATURE_PROPERTY = 'property';

    /**
     * @inheritDoc
     */
    protected const TYPE_DEFAULT = 'mixed';

    /**
     * @inheritDoc
     */
    public function allowedAsInline (): bool {
        return false;
    }

    /**
     * Try to parse the next parameter as a Constant/Property
     *
     * @param GenericElement $element The element to hydrate
     * @param array          $params  List of params (pop first element if match)
     *
     * @return bool Has the parameter match ?
     */
    protected function parseConstantProperty (GenericElement &$element, array &$params): bool {
        if (preg_match(RegexRegistry::CONSTANT_PROPERTY, $params[0], $match) === 1) {
            if(!empty($match['property'])) {
                $element->addAttribute('element', $match['property']);
                $element->addAttribute('nature', self::NATURE_PROPERTY);
            }
            elseif(!empty($match['constant'])) {
                $element->addAttribute('element', $match['constant']);
                $element->addAttribute('nature', self::NATURE_CONSTANT);
            }

            array_shift($params);
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    protected function parse (): GenericElement {
        /** @var TypeElement $element */
        $element = $this->createElement(TypeElement::class, true);
        $params = $this->getPayloadSplitted();

        if(count($params) == 0) {
            $type_raw = static::TYPE_DEFAULT;
        }
        else {

            $type_raw = $params[0];
            array_shift($params);

            $this->parseConstantProperty($element, $params);
        }

        $this->treatTypeExpression($element, $type_raw);
        $element->addChild(implode(' ', $params));

        return $element;
    }
}