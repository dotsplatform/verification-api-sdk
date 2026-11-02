<?php
/**
 * Description of VerificationHttpClientException.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Oleksandr Polosmak <o.polosmak@dotsplatform.com>
 */

namespace Dotsplatform\Verification\Exception;

use Exception;
use Throwable;

class VerificationHttpClientException extends Exception
{
    /**
     * @param array<mixed> $responseBody decoded JSON body of the failed answer, empty when it had none
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        private readonly array $responseBody = [],
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array<mixed>
     */
    public function getResponseBody(): array
    {
        return $this->responseBody;
    }

    public function getResponseMessage(): ?string
    {
        $responseMessage = $this->responseBody['message'] ?? null;
        if (! is_string($responseMessage)) {
            return null;
        }

        return $responseMessage;
    }

    public function getResponseErrorCode(): ?string
    {
        $responseErrorCode = $this->responseBody['error_code'] ?? null;
        if (! is_string($responseErrorCode)) {
            return null;
        }

        return $responseErrorCode;
    }
}
