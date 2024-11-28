<?php

use MargaTampu\LaravelTeamsLogging\Logger;
use MargaTampu\LaravelTeamsLogging\LoggerChannel;
use MargaTampu\LaravelTeamsLogging\LoggerHandler;

it('returns logger', function () {
    $channel = new LoggerChannel();
    /** @var Logger */
    $logger = $channel([
        'url' => 'test-url',
    ]);
    /** @var LoggerHandler */
    $handler = $logger->popHandler();

    expect($logger)->toBeInstanceOf(Logger::class);
    expect($handler)->toBeInstanceOf(LoggerHandler::class);
});
