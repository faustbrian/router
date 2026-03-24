<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Cline\CodingStandard\EasyCodingStandard\Factory;
use PhpCsFixer\Fixer\ClassNotation\FinalClassFixer;
use PhpCsFixer\Fixer\ClassNotation\FinalInternalClassFixer;
use PhpCsFixerCustomFixers\Fixer\NoNullableBooleanTypeFixer;
use PhpCsFixerCustomFixers\Fixer\ReadonlyPromotedPropertiesFixer;

return Factory::create(
    paths: [__DIR__.'/src', __DIR__.'/tests'],
    skip: [
        FinalClassFixer::class => [
            __DIR__.'/src/Attributes/Route.php',
            __DIR__.'/src/Attributes/Resource.php',
            __DIR__.'/src/Attributes/Where.php',
            __DIR__.'/tests/TestCase.php',
        ],
        ReadonlyPromotedPropertiesFixer::class => [
            __DIR__.'/src/Attributes/ApiResource.php',
            __DIR__.'/src/Attributes/Resource.php',
            __DIR__.'/src/Attributes/Route.php',
        ],
        NoNullableBooleanTypeFixer::class => [
            __DIR__.'/src/ClassRouteAttributes.php',
            __DIR__.'/src/Attributes/ApiResource.php',
            __DIR__.'/src/Attributes/Resource.php',
        ],
        FinalInternalClassFixer::class => [
            __DIR__.'/tests/TestCase.php',
        ],
    ],
);
