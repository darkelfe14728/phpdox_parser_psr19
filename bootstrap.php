<?php

namespace TheSeer\phpDox\Generator\Engine;

use TheSeer\phpDox\BootstrapApi;

/**
 * @var BootstrapApi $phpDox phpDox variable used to register parsers
 */
$phpDox->registerParser('psr19')
    ->implementedByClass('TheSeer\\phpDox\\Generator\\Parser\\Psr19Parser');
