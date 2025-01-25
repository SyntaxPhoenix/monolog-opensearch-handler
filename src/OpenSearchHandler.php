<?php

namespace SyntaxPhoenix\MonologOpenSearchHandler;

use Monolog\Logger;
use OpenSearch\Client;
use OpenSearch\ClientBuilder;
use Monolog\Formatter\ScalarFormatter;
use Monolog\Handler\AbstractProcessingHandler;

class OpenSearchHandler extends AbstractProcessingHandler
{
    public function __construct(
        string $endpoint,
        string $username = null,
        string $password = null,
        protected readonly string $index,
        int $level = Logger::DEBUG,
        bool $bubble = true,
        protected readonly ?Client $client = null
    ) {
        parent::__construct($level, $bubble);

        if ($this->client == null) {
            $builder = ClientBuilder::create()->setHosts([
                $endpoint
            ]);
            if ($username != null && $password != null) {
                $builder->setBasicAuthentication($username, $password);
            }
            $this->client = $builder->build();
        }
    }

    /**
     * @inheritDoc
     */
    protected function write(array $record): void
    {
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