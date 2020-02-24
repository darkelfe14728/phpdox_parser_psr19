<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\GenericElement;
use phpDoxExtension\Parser\PSR19\Utils\TypeElement;
use phpDoxExtension\Parser\PSR19\Utils\TypeParser;
use function count;
use function implode;
use function mb_substr;

/**
 * Class for "param" tag
 *
 * Syntax: [type] $name [description]
 *
 * Attributes:
 *  - type
 *  - name (with initial $)
 *
 * Body:
 *  - types details
 *  - description
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class ParamParser extends TypeParser {
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
     * @inheritDoc
     */
    protected function parse (): GenericElement {
        /** @var TypeElement $element */
        $element = $this->createElement(TypeElement::class, true);
        $payload = $this->getPayloadSplitted();

        switch(count($payload)) {
            case 0:
                $type_raw = static::TYPE_DEFAULT;
                $name = '$';
                $description = '';
            break;

            case 1:
                if (mb_substr($payload[0], 0, 1) === '$') {
                    $type_raw = static::TYPE_DEFAULT;
                    $name = $payload[0];
                }
                else {
                    $type_raw = $payload[0];
                    $name = '$';
                }
                $description = '';
            break;

            default:
                if (mb_substr($payload[0], 0, 1) === '$') {
                    $type_raw = static::TYPE_DEFAULT;
                    $name = $payload[0];
                    unset($payload[0]);
                }
                else {
                    $type_raw = $payload[0];
                    $name = $payload[1];
                    unset($payload[0]);
                    unset($payload[1]);
                }

                $description = implode(' ', $payload);
        }

        $this->treatTypeExpression($element, $type_raw);

        $element->addAttribute(TypeElement::TAG_NAME, $type_raw);
        $element->addAttribute('name', $name);
        $element->addChild($description);

        return $element;
    }
}