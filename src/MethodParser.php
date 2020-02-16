<?php

namespace phpDoxExtension\Parser\PSR19;

use phpDoxExtension\Parser\PSR19\Utils\GenericParser;
use TheSeer\phpDox\DocBlock\GenericElement;

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
class MethodParser extends GenericParser {
    /**
     * @inheritDoc
     */
    public function getObject (array $buffer): GenericElement {
        $obj = $this->buildObject('generic', $buffer);

        if (preg_match('@^(?:(?<return>[^ ]+)\s+)?(?<name>[a-z0-9_]+)\s*\((?<parameters>.+?)?\)\s*(?<description>.+)?$@i', $this->payload, $matches)) {
            $obj->setName($matches['name']);

            $body = rtrim($matches['description']).' ';
            if (!empty($matches['return'])) {
                $body .= ' {@return ' . $this->lookupType($matches['return']) . '}';
            }
            if (!empty($matches['parameters'])) {
                if (preg_match_all('@(?<=^|(?:, ))(?:(?<type>[^ ]+)\s+)?(?<var>\$[a-z0-9_]+)(?=(?:, )|$)@i', $matches['parameters'], $params, PREG_SET_ORDER)) {
                    foreach ($params as $param) {
                        $body .= ' {@param ' . $this->lookupType($param['type']) . ' ' . $param['var'] . '}';
                    }
                }
            }
        }
        else {
            $body = $this->payload;
        }
        $obj->setBody($body);

        return $obj;
    }
}