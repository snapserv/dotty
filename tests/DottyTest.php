<?php
/** @noinspection MultipleExpectChainableInspection */

declare(strict_types=1);

use SnapServ\Dotty\Dotty;

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
