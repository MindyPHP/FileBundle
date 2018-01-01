<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\FileBundle\Form\DataTransformer;

use League\Flysystem\FilesystemInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

class FileDataTransformer implements DataTransformerInterface
{
    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    /**
     * FileDataTransformer constructor.
     *
     * @param FilesystemInterface $filesystem
     */
    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if ($this->filesystem->has($value)) {
            $meta = $this->filesystem->getMetadata($value);

            return new File($meta['path'], false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        return $value;
    }
}
