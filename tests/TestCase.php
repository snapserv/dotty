<?php

declare(strict_types=1);

namespace SnapServ\Dotty\Tests;

use SnapServ\Dotty\DottyServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            DottyServiceProvider::class,
        ];
    }
}
