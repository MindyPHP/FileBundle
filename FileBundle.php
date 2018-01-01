<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\FileBundle;

use Oneup\FlysystemBundle\OneupFlysystemBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Created by PhpStorm.
 * User: max
 * Date: 12/12/2016
 * Time: 20:59.
 */
class FileBundle extends Bundle
{
    public function getParent()
    {
        return OneupFlysystemBundle::class;
    }
}
