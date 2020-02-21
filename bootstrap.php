<?php

namespace TheSeer\phpDox\DocBlock;

require_once __DIR__.'/vendor/autoload.php';

use TheSeer\phpDox\BootstrapApi;

/**
 * @var BootstrapApi $phpDox phpDox variable used to register parsers
 */
$phpDox->registerParser('description')->implementedByClass('phpDoxExtension\\Parser\\PSR19\\DescriptionParser');

$phpDox->registerParser('api')->implementedByClass('phpDoxExtension\\Parser\\PSR19\\ApiParser');
$phpDox->registerParser('author')->implementedByClass('phpDoxExtension\\Parser\\PSR19\\AuthorParser');
$phpDox->registerParser('copyright')->implementedByClass('phpDoxExtension\\Parser\\PSR19\\CopyrightParser');
$phpDox->registerParser('deprecated')->implementedByClass('phpDoxExtension\\Parser\\PSR19\\DeprecatedParser');
$phpDox->registerParser('internal')->implementedByClass('phpDoxExtension\\Parser\\PSR19\\InternalParser');
$phpDox->registerParser('link')->implementedByClass('phpDoxExtension\\Parser\\PSR19\\LinkParser');
$phpDox->registerParser('method')->implementedByClass('phpDoxExtension\\Parser\\PSR19\\MethodParser');
$phpDox->registerParser('package')->implementedByClass('phpDoxExtension\\Parser\\PSR19\PackageParser');
$phpDox->registerParser('param')->implementedByClass('phpDoxExtension\\Parser\\PSR19\PackageParser');
$phpDox->registerParser('return')->implementedByClass('phpDoxExtension\\Parser\\PSR19\\ReturnParser');
