<?php

namespace phpDoxExtension\Parser\PSR19\Utils;

use function array_shift;
use function preg_match;

/**
 * Parser for tag using a FQCN element
 *
 * Constants MUST be full upper case to be detect
 *
 * Attributes [FQSEN] :
 *  - class : FQCN
 *  - element : element name (with parenthesis, $, etc.)
 *  - elementType : nature of element (one of NATURE_* constant)
 *
 * @package phpDoxExtension\Parser\PSR19
 */
abstract class FQCNParser extends AbstractParser {
    /**
     * @var string Type attribute for constants
     */
    public const NATURE_CONSTANT = 'constant';
    /**
     * @var string Type attribute for properties
     */
    public const NATURE_PROPERTY = 'property';
    /**
     * @var string Type attribute for methdos
     */
    public const NATURE_METHOD = 'method';

    /**
     * Try to parse the next parameter as a FQCN
     *
     * @param GenericElement $element The element to hydrate
     * @param array          $params  List of params (pop first element if match)
     *
     * @return bool Has the parameter match ?
     */
    protected function parseFQCN (GenericElement &$element, array &$params): bool {
        if (preg_match(RegexRegistry::FQSEN, $params[0], $match) === 1) {
            $element->addAttribute('class',  $this->completeClassName($match['class']));

            if(!empty($match['property'])) {
                $element->addAttribute('element', $match['property']);
                $element->addAttribute('nature', self::NATURE_PROPERTY);
            }
            elseif(!empty($match['method'])) {
                $element->addAttribute('element', $match['method']);
                $element->addAttribute('nature', self::NATURE_METHOD);
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
}