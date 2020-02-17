<?php

namespace phpDoxExtension\Parser\PSR19\Utils;

/**
 * A generic element
 *
 * @package phpDoxExtension\Parser\PSR19\Utils
 */
class GenericElement extends \TheSeer\phpDox\DocBlock\GenericElement {
    /**
     * Convenient function to add attribute to final XML
     *
     * @param string $name The name of attribute
     * @param string $value The value of attribute
     */
    public function addAttribute (string $name, string $value): void {
        $this->attributes[$name] = $value;
    }
}