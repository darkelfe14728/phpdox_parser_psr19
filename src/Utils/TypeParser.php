<?php

namespace phpDoxExtension\Parser\PSR19\Utils;

/**
 * The abstract "type" parser used by tags
 *
 * @package phpDoxExtension\Parser\PSR19\Utils
 */
abstract class TypeParser extends AbstractParser {
    protected const TYPE_DEFAULT = 'void';

    /**
     * @var string[] List of type keywords (except specials)
     */
    protected static $keywords = ['array', 'bool', 'callable', 'false', 'float', 'int', 'iterable', 'mixed', 'null', 'object', 'resource', 'string', 'true', 'void'];
    /**
     * @var string[] List of specials type keywords : class references
     */
    protected static $references = ['self', 'static', '$this'];

    /**
     * @return string[] List of type keywords (include specials)
     */
    protected static function allKeywords (): array {
        return array_merge(static::$keywords, static::$references);
    }

    /**
     * Extract type information from payload
     *
     * @param array $payload The payload (splitted) to analyse. As reference to allow payload modification
     *
     * @return string The type
     */
    protected function extractType (array &$payload): string {
        if (count($payload)) {
            $type = empty($payload[0]) ? static::TYPE_DEFAULT : $payload[0];
            unset($payload[0]);
        }
        else {
            $type = static::TYPE_DEFAULT;
        }

        return $type;
    }

    /**
     * @inheritDoc
     */
    protected function parse (): GenericElement {
        /** @var TypeElement $element */
        $element = $this->createElement(TypeElement::class, true);
        $payload = $this->getPayloadSplitted();

        $this->treatTypeExpression($element, $this->extractType($payload));

        $element->addChild(implode(' ', $payload));

        return $element;
    }

    /**
     * Treat a type expression
     *
     * @param TypeElement $element  The "type" element to fill
     * @param string      $type_raw A type expression
     */
    private function treatTypeExpression (TypeElement $element, string $type_raw): void {
        $types = self::splitOn($type_raw, '|');
        foreach ($types as $type) {
            if (count(self::splitOn($type, '&')) > 1) {
                $this->treatTypeIntersect($element, $type);
            }
            else {
                $this->treatType($element, $type);
            }
        }
    }
    /**
     * Treat an intersect (&) in a type
     *
     * @param TypeElement $element  The "type" element to fill
     * @param string      $type_raw The type intersect
     */
    private function treatTypeIntersect (TypeElement $element, string $type_raw): void {
        /** @var TypeElement $intersect */
        $intersect = $this->createElement(TypeElement::class, true, TypeElement::TAG_NAME);
        $intersect->setType($type_raw, TypeElement::TYPE_INTERSECT);

        $types = self::splitOn($type_raw, '&');
        foreach ($types as $type) {
            $this->treatClassName($intersect, $type);
        }

        $element->addChild($intersect);
    }
    /**
     * Treat a type
     *
     * @param TypeElement $element  The "type" element to fill
     * @param string      $type_raw A type
     */
    private function treatType (TypeElement $element, string $type_raw): void {
        if (mb_substr($type_raw, -2) === '[]') {
            $this->treatArray($element, $type_raw);
        }
        elseif (in_array($type_raw, static::allKeywords())) {
            $this->treatKeyword($element, $type_raw);
        }
        else {
            $this->treatClassName($element, $type_raw);
        }
    }
    /**
     * Treat an array
     *
     * @param TypeElement $element  The "type" element to fill
     * @param string      $type_raw A type array
     */
    private function treatArray (TypeElement $element, string $type_raw): void {
        /** @var TypeElement $array */
        $array = $this->createElement(TypeElement::class, true, TypeElement::TAG_NAME);

        $type = mb_substr($type_raw, 0, mb_strlen($type_raw) - 2);
        if (preg_match('/^\((?<subtype>.+)\)$/', $type, $match)) {
            $array->setType($match['subtype'], TypeElement::TYPE_ARRAY);
            $this->treatTypeExpression($array, $match['subtype']);
        }
        else {
            $array->setType($type_raw, TypeElement::TYPE_ARRAY);
            $this->treatType($array, $type);
        }

        $element->addChild($array);
    }
    /**
     * Treat a class name
     *
     * @param TypeElement $element  The "type" element to fill
     * @param string      $type_raw A type array
     */
    private function treatClassName (TypeElement $element, string $type_raw): void {
        /** @var TypeElement $class */
        $class = $this->createElement(TypeElement::class, true, TypeElement::TAG_NAME);

        if (mb_substr($type_raw, 0, 1) === '\\') {                             // If fully qualifed class name
            $type = $type_raw;
        }
        elseif (array_key_exists($type_raw, $this->aliasMap)) {                     // If imported ("use" keyword)
            $type = $this->aliasMap[$type_raw];
        }
        elseif (array_key_exists('::context', $this->aliasMap)) {                   // There is an active namespace
            $type = $this->aliasMap['::context'] . '\\' . $type_raw;
        }
        else {                                                                                  // Nothing special : assume global class
            $type = $type_raw;
        }

        $class->setType($type_raw, TypeElement::TYPE_CLASS, $type);
        $element->addChild($class);
    }
    /**
     * Treat a keyword
     *
     * @param TypeElement $element  The "type" element to fill
     * @param string      $type_raw A type array
     */
    private function treatKeyword (TypeElement $element, string $type_raw): void {
        /** @var TypeElement $keyword */
        $keyword = $this->createElement(TypeElement::class, true, TypeElement::TAG_NAME);

        if (in_array($type_raw, static::$references)) {
            $keyword->setType($type_raw, TypeElement::TYPE_REFERENCE, $this->aliasMap['::unit']);
        }
        else {
            $keyword->setType($type_raw, TypeElement::TYPE_KEYWORD, $type_raw);
        }

        $element->addChild($keyword);
    }

    /**
     *  Split a string on a separator, not in a "()" group, into an array
     *
     * @param string $input The input string
     * @param string $separator The separator used to split
     *
     * @return string[] The resulting array
     */
    private static function splitOn (string $input, string $separator): array {
        preg_match_all('@[' . $separator . '()]@', $input, $matches, PREG_SET_ORDER + PREG_OFFSET_CAPTURE);

        $output = [];
        $start = 0;
        $groups = 0;
        foreach ($matches as $match) {
            switch($match[0][0]) {
                case '(':
                    $groups++;
                break;

                case ')':
                    $groups--;
                    if($groups < 0) {
                        $groups = 0;
                    }
                break;

                case $separator:
                    if($groups == 0) {
                        if($match[0][1] > $start) {
                            $output[] = mb_substr($input, $start, $match[0][1] - $start);
                        }
                        $start = $match[0][1] + 1;
                    }
                break;
            }
        }

        if(mb_strlen($input) > $start) {
            $output[] = mb_substr($input, $start);
        }

        return $output;
    }
}