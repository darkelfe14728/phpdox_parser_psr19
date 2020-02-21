<?php

namespace phpDoxExtension\Parser\PSR19\Utils;

/**
 * An element for 'type'
 *
 * @package phpDoxExtension\Parser\PSR19\Utils
 */
class TypeElement extends GenericElement {
    public const TAG_NAME = 'type';

    public const TYPE_INTERSECT = 'intersect';
    public const TYPE_ARRAY = 'array';
    public const TYPE_CLASS = 'class';
    public const TYPE_KEYWORD = 'keyword';
    public const TYPE_REFERENCE = 'reference';

    /**
     * Set the type
     *
     * @param string      $raw  The raw type
     * @param string      $type The type : one of TYPE_* constant
     * @param null|string $of   The subtype (for type TYPE_INTERSECT, TYPE_ARRAY or TYPE_REFERENCE)
     */
    public function setType (string $raw, string $type, ?string $of = null): void {
        $this->addAttribute('raw', $raw);
        $this->addAttribute('type', $type);
        if (!is_null($of)) {
            $this->addAttribute('of', $of);
        }
    }
}