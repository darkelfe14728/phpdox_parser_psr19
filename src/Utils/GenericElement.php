<?php

namespace phpDoxExtension\Parser\PSR19\Utils;

use DOMElement;
use DOMNode;
use TheSeer\fDOM\fDOMDocument;
use TheSeer\fDOM\fDOMElement;
use TheSeer\fDOM\fDOMException;
use TheSeer\phpDox\DocBlock\Factory;

/**
 * A generic element
 *
 * @package phpDoxExtension\Parser\PSR19\Utils
 */
class GenericElement extends \TheSeer\phpDox\DocBlock\GenericElement {
    /**
     * The phpDox XML namespace prefix for docblock XML
     */
    public const XML_PREFIX = 'http://xml.phpdox.net/src';

    /**
     * @var bool True if children must be scanned for "inline tags"
     */
    protected $allowInlineTag;

    /**
     * @inheritDoc
     *
     * @param bool $allowInlineTag True if children must be scanned for "inline tags"
     */
    public function __construct (Factory $factory, string $name, bool $allowInlineTag) {
        parent::__construct($factory, $name);
        $this->allowInlineTag = $allowInlineTag;
    }

    /**
     * Add / Replace an attribute for the output XML
     *
     * @param string $name  The name of the attribute
     * @param mixed  $value The value of the attribute
     */
    public function addAttribute (string $name, $value): void {
        $this->attributes[$name] = $value;
    }

    /**
     * Add a child for the output XML
     *
     * Child which are NOT string or {@see static}/{@link https://www.php.net/manual/en/class.domelement.php DOMElement} will be cast to string
     *
     * @param mixed $child The child to add
     */
    public function addChild ($child): void {
        if (is_string($child)
            || (is_object($child)
                && ($child instanceof static || $child instanceof DOMElement)
            )
        ) {
            $this->body[] = $child;
        }
        else {
            $this->body[] = (string)$child;
        }
    }

    /**
     * Transform the element into XML structure (DOM)
     *
     * @param fDOMDocument $ctx The DOMDocument used as context
     *
     * @return fDOMElement The XML structure (DOM)
     *
     * @throws fDOMException If an DOM operation failed
     */
    public function asDom (fDOMDocument $ctx): fDOMElement {
        $element = $ctx->createElementNS(self::XML_PREFIX, mb_strtolower($this->name));

        foreach ($this->attributes as $attribute_name => $attribute_value) {
            $element->setAttribute($attribute_name, $attribute_value);
        }

        if (!is_array($this->body)) {
            $this->body = [$this->body];
        }
        foreach ($this->body as $child) {
            if (is_object($child)) {
                if ($child instanceof DOMElement) {
                    $element->appendChild($ctx->importNode($child, true));
                }
                elseif ($child instanceof static) {
                    $element->appendChild($child->asDom($ctx));
                }
            }
            elseif (!empty($child)) {
                $element->appendChild($this->treatInlineTags($ctx, $child));
            }
        }

        return $element;
    }
    /**
     * Treat (if allowed) inline tags
     *
     * @param fDOMDocument $ctx  The DOMDocument used as context
     * @param string       $text The text to search inline tags
     *
     * @return DOMNode A node corresponding to text (with inline tags inside, if any)
     *
     * @throws fDOMException If an DOM operation failed
     */
    protected function treatInlineTags(fDOMDocument $ctx, string $text): DOMNode {
        if (!$this->allowInlineTag) {
            return $ctx->createTextNode($text);
        }

        $node = $ctx->createDocumentFragment();
        $start = 0;

        preg_match_all('/{@(?<tag>[a-zAZ0-9_]+)(?:\s+(?<payload>[^{}]*(?:(?R))?[^{}]*)|)}/s',$text, $matches, PREG_SET_ORDER + PREG_OFFSET_CAPTURE);
        foreach ($matches as $match) {
            if ($match[0][1] > $start) {
                $node->appendChild($ctx->createTextNode(mb_substr($text, $start, $match[0][1] - $start)));
                $start = $match[0][1];
            }

            $parser = $this->factory->getParserInstanceFor($match['tag'][0]);
            if ($parser instanceof AbstractParser && !$parser->allowedAsInline()) {
                $node->appendChild($ctx->createTextNode(mb_substr($text, $start, mb_strlen($match[0][0]))));
            }
            else {
                $parser->setPayload($match['payload'][0]);
                $node->appendChild($parser->getObject([])->asDom($ctx));
            }
            $start += mb_strlen($match[0][0]);
        }

        if (mb_strlen($text) > $start) {
            $node->appendChild($ctx->createTextNode(mb_substr($text, $start)));
        }

        return $node;
    }
}