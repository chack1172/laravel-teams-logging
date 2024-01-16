<?php

use MargaTampu\LaravelTeamsLogging\LoggerAvatar;
use MargaTampu\LaravelTeamsLogging\LoggerColour;
use MargaTampu\LaravelTeamsLogging\LoggerHandler;
use Monolog\LogRecord;

it('simple styling sets correct data', function () {
    $handler = new LoggerHandler('test');
    $message = $handler->useSimpleStyling('INFO', 'test message');
    $data = $message->jsonSerialize();
    expect($message)->toBeInstanceOf('MargaTampu\LaravelTeamsLogging\LoggerMessage');
    expect($data)->toMatchArray([
        '@context' => 'http://schema.org/extensions',
        '@type'    => 'MessageCard',
        'text'       => 'Default - <span style="color:#' . LoggerColour::INFO . '">INFO</span>: test message',
        'themeColor' => LoggerColour::INFO,
    ]);
});

it('card styling sets correct data', function () {
    $handler = new LoggerHandler('test');
    $level = 'INFO';
    $message = 'test message';
    $facts = ['extra' => 'facts'];
    $messageLog = $handler->useCardStyling($level, $message, $facts);
    $color = LoggerColour::INFO;

    expect($messageLog)->toMatchArray([
        '@context' => 'http://schema.org/extensions',
        '@type'    => 'MessageCard',
        'summary'    => "INFO: Default",
        'themeColor' => $color,
        'sections'   => [
            [
                'activityTitle'    => 'Default',
                'activitySubtitle' => 'test message',
                'facts'            => $facts,
                'markdown'         => true,
                'activityImage'    => LoggerAvatar::INFO,
                'activitySubtitle' => '<span style="color:#' . $color . '">test message</span>',
            ]
        ],
    ]);
});
