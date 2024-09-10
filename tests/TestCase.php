<?php declare(strict_types=1);

/**
 * This file is part of Guzzle To Symfony HTTP Client Adapter, a project of BitBasket, FZC.
 *
 * Copyright © 2024 BitBasket, FZC (SPCFZ, UAE).
 *   https://www.bitbasket.co/
 *   https://github.com/BitBasket/guzzle-to-symfony-adapter
 *
 * This file is licensed under the MIT License.
 */

namespace BitBasket\GuzzleToSymfonyAdapter\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Checks if phpunit was togged in debug mode o rnot.
     * See https://stackoverflow.com/a/12612733/430062.
     */
    public static function isDebugOn(): bool
    {
        return in_array('--debug', $_SERVER['argv'], true);
    }
}
