<?php

namespace MargaTampu\LaravelTeamsLogging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

class LoggerHandler extends AbstractProcessingHandler
{
    public function __construct(
        private string $url,
        int|string|Level $level = Level::Debug,
        private string $style = 'simple',
        private string $name = 'Default',
        bool $bubble = true
    ) {
        parent::__construct($level, $bubble);
    }

    protected function getMessage(LogRecord $record): LoggerMessage
    {
        if ($this->style == 'card') {
            // Include context as facts to send to microsoft teams
            // Added Sent Date Info

            $facts = [];
            foreach($record['context'] as $name => $value){
                $facts[] = ['name' => $name, 'value' => (string) $value];
            }

            $facts[] = [
                'name'  => 'Sent Date',
                'value' => date('D, M d Y H:i:s e'),
            ];

            if (!app()->runningInConsole()) {
                // Route
                if (config('teams.show_route', false)) {
                    $facts[] = [
                        'name'  => 'Route',
                        'value' => request()->getMethod() . ' : ' . request()->getPathInfo(),
                    ];
                }

                // (Controller) Action
                if (config('teams.show_action', false) && request()->route()) {
                    $facts[] = [
                        'name'  => 'Action',
                        'value' => request()->route()->getActionName(),
                    ];
                }
            }

            return $this->useCardStyling($record['level_name'], $record['message'], $facts);
        } else {
            return $this->useSimpleStyling($record['level_name'], $record['message']);
        }
    }

    /**
     * Styling message as simple message
     *
     * @param String $name
     * @param String $message
     * @param array  $facts
     */
    public function useCardStyling($name, $message, $facts)
    {
        $loggerColour = new LoggerColour($name);

        $loggerMessage = new LoggerMessage([
            'summary'    => $name . ($this->name ? ': ' . $this->name : ''),
            'themeColor' => (string) $loggerColour,
            'sections'   => [
                array_merge(config('teams.show_avatars', true) ? [
                    'activityTitle'    => $this->name,
                    'activitySubtitle' => $message,
                    'activityImage'    => (string) new LoggerAvatar($name),
                    'facts'            => $facts,
                    'markdown'         => true
                ] : [
                    'activityTitle'    => $this->name,
                    'activitySubtitle' => $message,
                    'facts'            => $facts,
                    'markdown'         => true
                ], config('teams.show_type', true) ? ['activitySubtitle' => '<span style="color:#' . (string) $loggerColour . '">' . $message . '</span>',] : [])
            ]
        ]);

        return $loggerMessage->jsonSerialize();
    }

    public function useSimpleStyling(string $name, string $message): LoggerMessage
    {
        $loggerColour = new LoggerColour($name);

        return new LoggerMessage([
            'text'       => ($this->name ? $this->name . ' - ' : '') . '<span style="color:#' . (string) $loggerColour . '">' . $name . '</span>: ' . $message,
            'themeColor' => (string) $loggerColour,
        ]);
    }

    protected function write(LogRecord $record): void
    {
        $json = json_encode($this->getMessage($record));

        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json)
        ]);

        curl_exec($ch);
    }
}
