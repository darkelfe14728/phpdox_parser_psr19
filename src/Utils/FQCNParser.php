<?php

namespace phpDoxExtension\Parser\PSR19\Utils;

use function array_shift;
use function preg_match;

/**
 * Parser for tag using a FQCN element
 *
 * Attributes [FQSEN] :
 *  - class : FQCN
 *  - element : element name (with parenthesis, $, etc.)
 *  - method : function name (without parenthesis)
 *  - property : property name (with $)
 *
 * @package phpDoxExtension\Parser\PSR19
 */
abstract class FQCNParser extends AbstractParser {
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
     * Try to parse the next parameter as a FQCN
     *
     * @param GenericElement $element The element to hydrate
     * @param array          $params  List of params (pop first element if match)
     *
     * @return bool Has the parameter match ?
     */
    protected function parseFQCN (GenericElement &$element, array &$params): bool {
        var_dump($params[0], preg_match(RegexRegistry::FQSEN, $params[0], $match), $match);
        if (preg_match(RegexRegistry::FQSEN, $params[0], $match) === 1) {
            $element->addAttribute('class',  $this->completeClassName($match['class']));

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
            return true;
        }

        return false;
    }
}