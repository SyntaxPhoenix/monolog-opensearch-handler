<?php

use Monolog\Level;
use Monolog\LogRecord;
use Monolog\Test\TestCase;
use OpenSearch\ClientBuilder;
use Monolog\Formatter\NormalizerFormatter;
use OpenSearch\Common\Exceptions\NoNodesAvailableException;
use SyntaxPhoenix\MonologOpenSearchHandler\OpenSearchHandler;
use SyntaxPhoenix\MonologOpenSearchHandler\OpenSearchFormatter;

class OpenSearchHandlerTest extends TestCase
{
    public function testConnectionErrors()
    {
        $client = OpenSearch\ClientBuilder::create()
            ->setHosts(['http://this.server.not.exists:9200'])
            ->build();

        $handler = new OpenSearchHandler('http://this.server.not.exists:9200', null, null, 'my_index', Level::Debug, true, $client);

        $this->expectException(NoNodesAvailableException::class);
        $handler->handle($this->getRecord());
    }

    protected function setUp(): void
    {
        $host = getenv('OPENSEARCH_HOST') ?: 'http://localhost:9200/';
        parent::setUp();

        $this->client = ClientBuilder::create()
            ->setHosts([$host])
            ->build();

        try {
            $this->client->info();
        } catch (Throwable) {
            $this->markTestSkipped("Could not connect to OpenSearch on $host");
        }
    }

    public function testLogWithOsFormatter()
    {
        $handler = new OpenSearchHandler($this->client, 'my_index');
        $handler->setFormatter(new OpenSearchFormatter());

        $uniqueMessage = uniqid(rand(), true);
        $handler->handle($this->getRecord(message: $uniqueMessage));
        sleep(1); // wait for node update

        $search = $this->client->search([
            'index' => 'my_index',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'match' => [
                                    "message" => $uniqueMessage
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertCount(1, $search['hits']['hits']);
    }

    public function testLogWithCustomFormatter()
    {
        $handler = new OpenSearchHandler($this->client, 'my_index');
        $handler->setFormatter(new class extends NormalizerFormatter {

            public function __construct()
            {
                parent::__construct(DateTimeInterface::ATOM);
            }

            public function format(LogRecord $record): array
            {
                $format = parent::format($record);
                $datetime = $format['datetime'];
                unset($format['datetime']);
                return [
                    'origin' => strtolower("{$record->channel}.{$record->level->name}"),
                    'created_at' => $datetime,
                    'data' => $format
                ];
            }
        });

        $handler->handle($this->getRecord());
    }
}