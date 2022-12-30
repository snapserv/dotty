<?php

declare(strict_types=1);

namespace SnapServ\Dotty\Tests;

use SnapServ\Dotty\DottyException;

it('provides a context method with custom data', function () {
    $exception = new DottyException('Test', ['a' => 'hello', 'b' => 42]);

    expect($exception->context())
        ->a->toEqual('hello')
        ->b->toEqual(42);
});
