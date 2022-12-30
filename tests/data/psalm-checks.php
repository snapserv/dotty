<?php

declare(strict_types=1);

use SnapServ\Dotty\Dotty;

/** @psalm-suppress UnusedVariable */
function testCases(): void
{
    $dotty = Dotty::from([]);

    /** @psalm-check-type-exact $sub = \SnapServ\Dotty\Dotty */
    $sub = $dotty->sub('child');

    /** @psalm-check-type-exact $bool1 = bool */
    $bool1 = $dotty->bool('key');
    /** @psalm-check-type-exact $bool2 = bool */
    $bool2 = $dotty->bool('key', true);
    /** @psalm-check-type-exact $bool3 = bool */
    $bool3 = $dotty->bool('key', fn() => true);
    /** @psalm-check-type-exact $bool4 = bool|null */
    $bool4 = $dotty->bool('key', required: false);

    /** @psalm-check-type-exact $int1 = int */
    $int1 = $dotty->int('key');
    /** @psalm-check-type-exact $int2 = int */
    $int2 = $dotty->int('key', 42);
    /** @psalm-check-type-exact $int3 = int */
    $int3 = $dotty->int('key', fn() => 42);
    /** @psalm-check-type-exact $int4 = int|null */
    $int4 = $dotty->int('key', required: false);

    /** @psalm-check-type-exact $float1 = float */
    $float1 = $dotty->float('key');
    /** @psalm-check-type-exact $float2 = float */
    $float2 = $dotty->float('key', 4.2);
    /** @psalm-check-type-exact $float3 = float */
    $float3 = $dotty->float('key', fn() => 4.2);
    /** @psalm-check-type-exact $float4 = float|null */
    $float4 = $dotty->float('key', required: false);

    /** @psalm-check-type-exact $string1 = string */
    $string1 = $dotty->string('key');
    /** @psalm-check-type-exact $string2 = string */
    $string2 = $dotty->string('key', 'default');
    /** @psalm-check-type-exact $string3 = string */
    $string3 = $dotty->string('key', fn() => 'default');
    /** @psalm-check-type-exact $string4 = string|null */
    $string4 = $dotty->string('key', required: false);

    /** @psalm-check-type-exact $array1 = array */
    $array1 = $dotty->array('key');
    /** @psalm-check-type-exact $array2 = array */
    $array2 = $dotty->array('key', ['a' => 1, 'b' => 2]);
    /** @psalm-check-type-exact $array3 = array */
    $array3 = $dotty->array('key', fn() => ['a' => 1, 'b' => 2]);
    /** @psalm-check-type-exact $array4 = array|null */
    $array4 = $dotty->array('key', required: false);
}

testCases();
