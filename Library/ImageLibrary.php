<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\FileBundle\Library;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Mindy\Template\Library\AbstractLibrary;

/**
 * Class ImageLibrary.
 */
class ImageLibrary extends AbstractLibrary
{
    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * ImageLibrary constructor.
     *
     * @param CacheManager        $cacheManager
     */
    public function __construct(CacheManager $cacheManager = null)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @return array
     */
    public function getHelpers()
    {
        return [
            'imagine_filter' => [$this->cacheManager, 'getBrowserPath'],
        ];
    }
}
