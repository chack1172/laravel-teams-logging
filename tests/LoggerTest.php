<?php

use MargaTampu\LaravelTeamsLogging\Logger;
use MargaTampu\LaravelTeamsLogging\LoggerHandler;

it('adds logger handler to monolog handlers', function () {
    $logger = new Logger('test-url');
    $handlers = $logger->getHandlers();
    $checkHandler = null;
    foreach ($handlers as $handler) {
        if ($handler instanceof LoggerHandler) {
            $checkHandler = $handler;
            break;
        }
    }

    expect($checkHandler)->toBeInstanceOf(LoggerHandler::class);
});
