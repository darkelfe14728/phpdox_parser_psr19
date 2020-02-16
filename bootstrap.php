<?php

namespace TheSeer\phpDox\DocBlock;

use TheSeer\phpDox\BootstrapApi;

/**
 * @var BootstrapApi $phpDox phpDox variable used to register parsers
 */
$phpDox->registerParser('api')->implementedByClass('TheSeer\\phpDox\\DocBlock\\ApiParser');
$phpDox->registerParser('author')->implementedByClass('TheSeer\\phpDox\\DocBlock\\AuthorParser');
