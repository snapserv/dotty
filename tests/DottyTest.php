<?php
/** @noinspection MultipleExpectChainableInspection */

declare(strict_types=1);

use SnapServ\Dotty\Dotty;
use SnapServ\Dotty\DottyException;

it('can be initialized with empty data', function () {
    expect(Dotty::empty())->toBeInstanceOf(Dotty::class);
});

it('can be initialized with regular array', function () {
    $data = ['a' => 1, 'b' => 2, 'c' => 3];

    expect(Dotty::from($data))
        ->toBeInstanceOf(Dotty::class)
        ->get('a')->toEqual(1)
        ->get('b')->toEqual(2)
        ->get('c')->toEqual(3);
});

it('can be initialized with ArrayAccess instance', function () {
    $data = new ArrayObject(['a' => 1, 'b' => 2, 'c' => 3]);

    expect(Dotty::from($data))
        ->toBeInstanceOf(Dotty::class)
        ->get('a')->toEqual(1)
        ->get('b')->toEqual(2)
        ->get('c')->toEqual(3);
});

it('can be initialized with Arrayable instance', function () {
    $data = collect(['a' => 1, 'b' => 2, 'c' => 3]);

    expect(Dotty::from($data))
        ->toBeInstanceOf(Dotty::class)
        ->get('a')->toEqual(1)
        ->get('b')->toEqual(2)
        ->get('c')->toEqual(3);
});

it('can check existence of regular keys', function () {
    $dotty = Dotty::from(['a' => 'b', 'x' => 'y']);

    // check individual keys for existence
    expect($dotty->has('a'))->toBeTrue();
    expect($dotty->has('b'))->toBeFalse();
    expect($dotty->has('x'))->toBeTrue();
    expect($dotty->has('y'))->toBeFalse();

    // check multiple keys for existence (AND)
    expect($dotty->has(['a', 'x']))->toBeTrue();
    expect($dotty->has(['a', 'b']))->toBeFalse();

    // check multiple keys for existence (OR)
    expect($dotty->hasAny(['a', 'x']))->toBeTrue();
    expect($dotty->hasAny(['a', 'b']))->toBeTrue();
    expect($dotty->hasAny(['b', 'y']))->toBeFalse();
});

it('can check existence of nested keys', function () {
    $dotty = Dotty::from(['a' => ['b' => 'c', 'd' => 'e'], 'x' => 'y']);

    // check individual keys for existence
    expect($dotty->has('a.b'))->toBeTrue();
    expect($dotty->has('a.b.c'))->toBeFalse();
    expect($dotty->has('a.c'))->toBeFalse();
    expect($dotty->has('a.d'))->toBeTrue();

    // check multiple keys for existence (AND)
    expect($dotty->has(['a.b', 'a.d']))->toBeTrue();
    expect($dotty->has(['a.b', 'x']))->toBeTrue();
    expect($dotty->has(['a.c', 'x']))->toBeFalse();

    // check multiple keys for existence (OR)
    expect($dotty->hasAny(['a.b', 'a.d']))->toBeTrue();
    expect($dotty->hasAny(['a.b', 'x']))->toBeTrue();
    expect($dotty->hasAny(['a.c', 'x']))->toBeTrue();
    expect($dotty->hasAny(['a.c', 'y']))->toBeFalse();
});

it('can provide child instance based on key', function () {
    $dotty = Dotty::from([
        'simple' => ['a' => 1, 'b' => 2, 'c' => 3],
        'nested' => ['child' => ['x' => 1, 'y' => 2], 'other' => ['x' => 2, 'y' => 1]],
    ]);

    expect($dotty->sub('simple'))
        ->toBeInstanceOf(Dotty::class)
        ->get('a')->toEqual(1)
        ->get('b')->toEqual(2)
        ->get('c')->toEqual(3);

    expect($dotty->sub('nested.child'))
        ->toBeInstanceOf(Dotty::class)
        ->get('x')->toEqual(1)
        ->get('y')->toEqual(2);
});

it('throws an error when key for child instance is missing', function () {
    $dotty = Dotty::from(['simple' => ['a' => 1, 'b' => 2, 'c' => 3]]);

    expect(fn() => $dotty->sub('doesnotexist'))
        ->toThrow(DottyException::class, 'missing key [doesnotexist]');
});

it('throws an error when value for child instance is invalid', function () {
    $dotty = Dotty::from(['simple' => 42]);

    expect(fn() => $dotty->sub('simple'))
        ->toThrow(
            DottyException::class,
            'illegal type for [simple], expected [array|ArrayAccess|Arrayable]',
        );
});

it('can access regular mixed keys', function () {
    $dotty = Dotty::from(['a' => 'hello', 'b' => 'world']);

    expect($dotty->get('a'))->toEqual('hello');
    expect($dotty->get('b'))->toEqual('world');
});

it('can access nested mixed keys', function () {
    $dotty = Dotty::from([
        'a' => ['b' => 'hello', 'c' => 'world'],
        'd' => ['e' => ['f' => 'test', 'g' => 'scenario']],
    ]);

    expect($dotty->get('a.b'))->toEqual('hello');
    expect($dotty->get('a.c'))->toEqual('world');
    expect($dotty->get('d.e'))->toEqual(['f' => 'test', 'g' => 'scenario']);
    expect($dotty->get('d.e.f'))->toEqual('test');
    expect($dotty->get('d.e.g'))->toEqual('scenario');
});
