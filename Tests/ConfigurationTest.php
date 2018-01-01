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

use Mindy\Bundle\FileBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    public function testConfiguration()
    {
        $configuration = new Configuration();

        $node = $configuration->getConfigTreeBuilder()->buildTree();

        $config = $node->finalize($node->normalize([
            'default_filesystem' => 'default',
        ]));

        $this->assertEquals([
            'default_filesystem' => 'default',
        ], $config);
    }
}
