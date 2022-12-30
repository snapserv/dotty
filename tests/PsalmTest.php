<?php

declare(strict_types=1);

namespace SnapServ\Dotty\Tests;

use Symfony\Component\Process\Process;

it('throws no errors when running psalm checks', function () {
    $process = new Process([
        __DIR__ . '/../vendor/bin/psalm',
        '--no-progress',
        __DIR__ . '/data/psalm-checks.php',
    ]);

    $result = $process->mustRun();
    expect($result->getExitCode())
        ->toBeInt()
        ->toEqual(0);
});
