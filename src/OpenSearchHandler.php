<?php

namespace SyntaxPhoenix\MonologOpenSearchHandler;

use Monolog\Logger;
use OpenSearch\Client;
use OpenSearch\SymfonyClientFactory;
use Monolog\Formatter\ScalarFormatter;
use Monolog\Handler\AbstractProcessingHandler;

class OpenSearchHandler extends AbstractProcessingHandler
{
    public function __construct(
        private string $endpoint,
        protected readonly string $index,
        private ?string $username = null,
        private ?string $password = null,
        private int $level = Logger::DEBUG,
        private bool $bubble = true,
        protected ?Client $client = null
    ) {
        parent::__construct($level, $bubble);
    }

    /**
     * @inheritDoc
     */
    protected function write(array $record): void
    {
        if ($this->client == null) {
            $settings = [
                'base_uri' => $this->endpoint,
                'verify_peer' => false,
            ];

            if ($this->username != null && $this->password != null) {
                $settings['auth_basic'] = [$this->username, $this->password];
            }

            $this->client = (new SymfonyClientFactory())->create($settings);
        }

        $this->client->create([
            'index' => $this->index,
            'body' => $record['formatted']
        ]);
    }

    public function getDefaultFormatter(): ScalarFormatter
    {
        return new ScalarFormatter();
    }
}