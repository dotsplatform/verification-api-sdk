<?php
/**
 * Description of ConfirmVerificationTest.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dotsplatform\Verification\Tests\Unit;

use Dotsplatform\Verification\Exception\VerificationCodeException;
use Dotsplatform\Verification\Exception\VerificationCodeLockedException;
use Dotsplatform\Verification\Tests\Unit\Support\FakeHttpVerificationClient;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Throwable;

class ConfirmVerificationTest extends TestCase
{
    private const ACCOUNT_ID = 'account-id';
    private const PHONE = '380501234567';
    private const CODE = '1234';

    public function testCodeLockedThrowsVerificationCodeLockedException(): void
    {
        $client = FakeHttpVerificationClient::answering(400, [
            'message' => 'Code is invalid',
            'error_code' => 'CODE_LOCKED',
        ]);

        $exception = $this->catchConfirmException($client);

        $this->assertInstanceOf(VerificationCodeLockedException::class, $exception);
        $this->assertSame(400, $exception->getCode());
    }

    public function testCodeLockedIsStillCaughtAsVerificationCodeException(): void
    {
        $client = FakeHttpVerificationClient::answering(400, [
            'message' => 'Code is invalid',
            'error_code' => 'CODE_LOCKED',
        ]);

        $this->expectException(VerificationCodeException::class);

        $client->confirm(self::ACCOUNT_ID, self::PHONE, self::CODE);
    }

    public function testInvalidCodeThrowsPlainVerificationCodeException(): void
    {
        $client = FakeHttpVerificationClient::answering(400, [
            'message' => 'Code is invalid',
            'error_code' => 'INVALID_CODE',
        ]);

        $exception = $this->catchConfirmException($client);

        $this->assertSame(VerificationCodeException::class, $exception::class);
        $this->assertSame(400, $exception->getCode());
    }

    public function testAnswerWithoutErrorCodeThrowsPlainVerificationCodeException(): void
    {
        $client = FakeHttpVerificationClient::answering(400, ['message' => 'Code is invalid']);

        $exception = $this->catchConfirmException($client);

        $this->assertSame(VerificationCodeException::class, $exception::class);
        $this->assertSame(400, $exception->getCode());
    }

    public function testAnswerWithNonJsonBodyThrowsPlainVerificationCodeException(): void
    {
        $client = new FakeHttpVerificationClient(new Response(400, [], '<html>Bad Request</html>'));

        $exception = $this->catchConfirmException($client);

        $this->assertSame(VerificationCodeException::class, $exception::class);
    }

    public function testRightCodeThrowsNothing(): void
    {
        $client = FakeHttpVerificationClient::answering(200, ['message' => 'ok']);

        $client->confirm(self::ACCOUNT_ID, self::PHONE, self::CODE);

        $this->addToAssertionCount(1);
    }

    private function catchConfirmException(FakeHttpVerificationClient $client): Throwable
    {
        try {
            $client->confirm(self::ACCOUNT_ID, self::PHONE, self::CODE);
        } catch (Throwable $exception) {
            return $exception;
        }
        $this->fail('confirm() was expected to throw');
    }
}
