<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\FileBundle\Tests;

use Mindy\Bundle\FileBundle\DependencyInjection\FileExtension;
use Mindy\Bundle\FileBundle\Form\DataTransformer\FileDataTransformer;
use Mindy\Bundle\FileBundle\Library\ImageLibrary;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ExtensionTest extends TestCase
{
    public function testLoad()
    {
        $container = new ContainerBuilder();
        $extension = new FileExtension();

        $extension->load([
            'file' => ['default_filesystem' => 'default'],
        ], $container);

        $expected = 'oneup_flysystem.default_filesystem';

        $this->assertSame($expected, $container->getParameter('file.filesystem'));

        /** @var Reference $reference */
        $definition = $container->getDefinition(ImageLibrary::class);
        $reference = $definition->getArgument(0);
        $this->assertSame($expected, (string) $reference);

        $definition = $container->getDefinition(FileDataTransformer::class);
        $reference = $definition->getArgument(0);
        $this->assertSame($expected, (string) $reference);
    }
}
