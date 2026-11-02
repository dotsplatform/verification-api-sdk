<?php
/**
 * Description of FakeHttpVerificationClient.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dotsplatform\Verification\Tests\Unit\Support;

use Dotsplatform\Verification\VerificationClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

/**
 * VerificationClient that answers every request with one prepared HTTP response
 * instead of calling the verification service (and without reading Laravel config).
 */
class FakeHttpVerificationClient extends VerificationClient
{
    public function __construct(Response $preparedResponse)
    {
        $this->serviceHost = 'https://verification.test/';
        $this->gatewayToken = 'test-token';
        $this->client = new GuzzleClient([
            'base_uri' => $this->serviceHost,
            'handler' => HandlerStack::create(new MockHandler([$preparedResponse])),
        ]);
    }

    /**
     * @param array<mixed> $body
     */
    public static function answering(int $statusCode, array $body): self
    {
        return new self(new Response(
            $statusCode,
            ['Content-Type' => 'application/json'],
            json_encode($body, JSON_THROW_ON_ERROR),
        ));
    }
}
