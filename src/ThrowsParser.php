<?php

namespace phpDoxExtension\Parser\PSR19;

use Exception;
use phpDoxExtension\Parser\PSR19\Utils\TypeElement;
use phpDoxExtension\Parser\PSR19\Utils\TypeParser;

/**
 * Class for "throws" tag
 *
 * Syntax: type [description]
 *
 * Type MUST be a class and inherit Throwable
 *
 * Attributes:
 *  - type
 *
 * Body:
 *  - types details
 *  - description
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class ThrowsParser extends TypeParser {
    /**
     * @inheritDoc
     */
    protected const TYPE_DEFAULT = Exception::class;

    /**
     * @inheritDoc
     */
    protected static $keywords = [];
    /**
     * inheritDoc
     */
    protected static $references = [];

    /**
     * @inheritDoc
     */
    public function allowedAsInline (): bool {
        return false;
    }

    /**
     * @inheritDoc
     */
    protected function treatTypeExpression (TypeElement $element, string $type_raw): void {
        $this->treatClassName($element, $type_raw);
    }
}