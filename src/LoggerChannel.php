<?php

namespace MargaTampu\LaravelTeamsLogging;

use Monolog\Level;

class LoggerChannel
{
    /**
     * @param array $config
     *
     * @return TeamsLogger
     */
    public function __invoke(array $config)
    {
        return new Logger($config['url'], $config['level'] ?? Level::Debug, $config['style'] ?? 'simple', $config['name'] ?? null);
    }
}
