<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\AbstractParser;
use phpDoxExtension\Parser\PSR19\Utils\GenericElement;

/**
 * Class for method tag
 *
 * Syntax: [return] (method]([[param1_type] param1_name, ...]) [description]
 *
 * Attributes :
 *  - name : method name
 *
 * Body :
 *  - return
 *  - parameters
 *  - description of method
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class MethodParser extends AbstractParser {
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
        $element = $this->createElement(GenericElement::class, true);

        if (preg_match('@^(?:(?<return>[^ ]+)\s+)?(?<name>[a-z0-9_]+)\s*\((?<parameters>.+?)?\)\s*(?<description>.+)?$@i', $this->getPayload(), $matches)) {
            $element->addAttribute('name', $matches['name']);

//            if (!empty($matches['return'])) {
//                $description .= ' {@return ' . $this->lookupType($matches['return']) . '}';
//            }
//            if (!empty($matches['parameters'])) {
//                if (preg_match_all('@(?<=^|(?:, ))(?:(?<type>[^ ]+)\s+)?(?<var>\$[a-z0-9_]+)(?=(?:, )|$)@i', $matches['parameters'], $params, PREG_SET_ORDER)) {
//                    foreach ($params as $param) {
//                        $description .= ' {@param ' . $this->lookupType($param['type']) . ' ' . $param['var'] . '}';
//                    }
//                }
//            }

            $description = rtrim($matches['description']);
        }
        else {
            $description = $this->getPayload();
        }
        $element->addChild($description);

        return $element;
    }
}