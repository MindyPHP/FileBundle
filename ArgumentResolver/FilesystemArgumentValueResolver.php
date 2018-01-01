<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\FileBundle\ArgumentResolver;

use League\Flysystem\FilesystemInterface;
use League\Flysystem\MountManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class FilesystemArgumentValueResolver implements ArgumentValueResolverInterface
{
    /**
     * @var MountManager
     */
    protected $mountManager;

    /**
     * FilesystemArgumentValueResolver constructor.
     *
     * @param MountManager $mountManager
     */
    public function __construct(MountManager $mountManager)
    {
        $this->mountManager = $mountManager;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->getType() instanceof FilesystemInterface;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $filesystem = $this->mountManager->getFilesystem('default');

        $request->attributes->set($argument->getName(), $filesystem);
    }
}
