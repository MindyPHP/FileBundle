<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\FileBundle\DependencyInjection;

use Mindy\Bundle\FileBundle\Form\DataTransformer\FileDataTransformer;
use Mindy\Bundle\FileBundle\Library\ImageLibrary;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class FileExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $fs = sprintf('oneup_flysystem.%s_filesystem', $config['default_filesystem']);
        $container->setParameter('file.filesystem', $fs);

        $container
            ->getDefinition(FileDataTransformer::class)
            ->setArguments([new Reference($fs)]);

        $container
            ->getDefinition(ImageLibrary::class)
            ->setArguments([new Reference($fs)]);
    }
}
