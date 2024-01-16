<?php

use MargaTampu\LaravelTeamsLogging\LoggerMessage;

it('offset exists', function () {
    $message = new LoggerMessage(['test' => true]);

    expect($message->offsetExists('test'))->toBeTrue();
    expect($message->offsetExists('key'))->toBeFalse();
});

it('offset get', function () {
    $message = new LoggerMessage(['test' => 'message']);

    expect($message->offsetGet('test'))->toEqual('message');
    expect($message->offsetGet('key'))->toBeNull();
});

it('offset set', function () {
    $message = new LoggerMessage([]);

    $message->offsetSet(null, 'message');
    $message->offsetSet('test', 'key message');

    expect($message->offsetGet(0))->toEqual('message');
    expect($message->offsetGet('test'))->toEqual('key message');
});

it('offset unset', function () {
    $message = new LoggerMessage(['test' => 'message']);

    $message->offsetUnset('test');

    expect($message->offsetGet('test'))->toBeNull();
});
