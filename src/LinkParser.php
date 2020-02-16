<?php

namespace phpDoxExtension\Parser\PSR19;

use TheSeer\phpDox\DocBlock\GenericElement;
use TheSeer\phpDox\DocBlock\GenericParser;

/**
 * Class for link tag
 *
 * Syntax : URI [description]
 *
 * URI MUST respect RFC-2396 to be detected
 *
 * Attributes :
 *  - uri : URI
 *
 * Body : description
 *
 * @package phpDoxExtension\Parser\PSR19
 */
class LinkParser extends GenericParser {
    /**
     * @var string The RFC-2396 URI regular expression
     */
    public const REGEX_URI_RFC2396 = /** @lang PhpRegExp */ '@^(?:(?:[^:/?#]+):)?(?://(?:[^/?#]*))?(?:[^?#]*)(?:\?(?:[^#]*))?(?:#(?:.*))?$@';

    /**
     * @inheritDoc
     */
    public function getObject (array $buffer): GenericElement {
        $obj = $this->buildObject('generic', $buffer);

        $params = preg_split("/\s+/", $this->payload, -1, PREG_SPLIT_NO_EMPTY);

        if (!empty($params[0]) && preg_match(self::REGEX_URI_RFC2396, $params[0], $matches) === 1) {
            $obj->setUri($params[0]);
            array_pop($params);
        }

        $obj->setBody(implode(' ', $params));

        return $obj;
    }
}