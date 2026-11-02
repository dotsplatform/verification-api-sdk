<?php
/**
 * Description of StartVerificationTest.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dotsplatform\Verification\Tests\Unit;

use Dotsplatform\Verification\Exception\TooManyVerificationAttempts;
use Dotsplatform\Verification\Tests\Unit\Support\FakeHttpVerificationClient;
use Dotsplatform\Verification\VerificationType;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class StartVerificationTest extends TestCase
{
    private const ACCOUNT_ID = 'account-id';
    private const PHONE = '380501234567';

    public function testServiceSendLimitAnsweredWith400ThrowsTooManyAttemptsWithCode429(): void
    {
        $client = FakeHttpVerificationClient::answering(400, ['message' => 'Too many attempts']);

        $this->expectException(TooManyVerificationAttempts::class);
        $this->expectExceptionCode(429);

        $client->startVerification(self::ACCOUNT_ID, self::PHONE);
    }

    public function testAnswer429ThrowsTooManyAttempts(): void
    {
        $client = FakeHttpVerificationClient::answering(429, []);

        $this->expectException(TooManyVerificationAttempts::class);
        $this->expectExceptionCode(429);

        $client->startVerification(self::ACCOUNT_ID, self::PHONE);
    }

    public function testOther400IsSwallowedAsBefore(): void
    {
        $client = FakeHttpVerificationClient::answering(400, ['message' => 'User is banned']);

        $response = $client->startVerification(self::ACCOUNT_ID, self::PHONE);

        // Unchanged behaviour: the error is swallowed and the default (SMS) answer is returned.
        $this->assertSame(VerificationType::SMS, $response->getVerificationType());
    }

    public function testOther400WithNonJsonBodyIsSwallowedAsBefore(): void
    {
        $client = new FakeHttpVerificationClient(new Response(400, [], 'Bad Request'));

        $response = $client->startVerification(self::ACCOUNT_ID, self::PHONE);

        // Unchanged behaviour: the error is swallowed and the default (SMS) answer is returned.
        $this->assertSame(VerificationType::SMS, $response->getVerificationType());
    }

    public function testSuccessfulStartReturnsVerificationType(): void
    {
        $client = FakeHttpVerificationClient::answering(200, ['verificationType' => VerificationType::CALL]);

        $response = $client->startVerification(self::ACCOUNT_ID, self::PHONE);

        $this->assertSame(VerificationType::CALL, $response->getVerificationType());
    }
}
