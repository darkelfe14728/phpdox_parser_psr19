<?php

namespace Tests\src;

use phpDoxExtension\Parser\PSR19\ApiParser;
use phpDoxExtension\Parser\PSR19\Utils\AbstractParser;
use phpDoxExtension\Parser\PSR19\Utils\TypeParser;

class ReturnClass {
    /**
     * @return array|bool|callable|false|float|int|iterable|mixed|null|object|resource|string|true|void|self|static|$this|Range|string[]|ApiParser|AbstractParser&TypeParser|(string[]|null)[] keyword
     */
    public function method1() {
        return null;
    }
}