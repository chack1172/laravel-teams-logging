<?php

use MargaTampu\LaravelTeamsLogging\LoggerAvatar;
use MargaTampu\LaravelTeamsLogging\LoggerColour;
use MargaTampu\LaravelTeamsLogging\LoggerHandler;

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
    $data = $messageLog->jsonSerialize();
    $color = LoggerColour::INFO;

    expect($data)->toMatchArray([
        '@context' => 'http://schema.org/extensions',
        '@type'    => 'MessageCard',
        'summary'    => "INFO: Default",
        'themeColor' => $color,
        'sections'   => [
            [
                'activityTitle'    => 'Default',
                'facts'            => $facts,
                'markdown'         => true,
                'activityImage'    => LoggerAvatar::INFO,
                'activitySubtitle' => '<span style="color:#' . $color . '">test message</span>',
            ]
        ],
    ]);
});

it('sets correct card styling with avatars disabled', function () {
    config()->set('teams.show_avatars', false);

    $handler = new LoggerHandler('test');
    $level = 'INFO';
    $message = 'test message';
    $facts = ['extra' => 'facts'];
    $messageLog = $handler->useCardStyling($level, $message, $facts);
    $color = LoggerColour::INFO;

    expect($messageLog['sections'][0])->toMatchArray([
        'activityTitle'    => 'Default',
        'facts'            => $facts,
        'markdown'         => true,
        'activitySubtitle' => '<span style="color:#' . $color . '">test message</span>',
    ]);
});

it('sets correct card styling with type disabled', function () {
    config()->set('teams.show_type', false);

    $handler = new LoggerHandler('test');
    $level = 'INFO';
    $message = 'test message';
    $facts = ['extra' => 'facts'];
    $messageLog = $handler->useCardStyling($level, $message, $facts);

    expect($messageLog['sections'][0])->toMatchArray([
        'activityTitle'    => 'Default',
        'facts'            => $facts,
        'markdown'         => true,
        'activityImage'    => LoggerAvatar::INFO,
        'activitySubtitle' => 'test message',
    ]);
});
