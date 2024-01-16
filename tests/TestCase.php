<?php

namespace MargaTampu\LaravelTeamsLogging\Tests;

use MargaTampu\LaravelTeamsLogging\LoggerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LoggerServiceProvider::class,
        ];
    }
}
