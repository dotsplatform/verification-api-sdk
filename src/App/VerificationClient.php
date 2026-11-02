<?php
/**
 * Description of VerificationClient.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Oleksandr Polosmak <o.polosmak@dotsplatform.com>
 */

namespace Dotsplatform\Verification;

use Dotsplatform\Verification\DTO\CodesDTO;
use Dotsplatform\Verification\DTO\CodesFiltersDTO;
use Dotsplatform\Verification\DTO\StartVerificationResponseDTO;
use Dotsplatform\Verification\DTO\UserDTO;
use GuzzleHttp\Exception\ClientException;
use Dotsplatform\Verification\DTO\StoreAccountDTO;
use Dotsplatform\Verification\DTO\VerificationAccountSettingsDTO;
use Dotsplatform\Verification\Exception\TooManyVerificationAttempts;
use Dotsplatform\Verification\Exception\VerificationCodeException;
use Dotsplatform\Verification\Exception\VerificationCodeLockedException;
use Dotsplatform\Verification\Exception\VerificationHttpClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use RuntimeException;

class VerificationClient extends HttpClient
{
    private const STORE_ACCOUNT_URL_TEMPLATE = '/api/accounts';
    private const FIND_ACCOUNT_URL_TEMPLATE = '/api/accounts/%s';
    private const STORE_USER_URL_TEMPLATE = '/api/accounts/%s/users';
    private const FIND_USER_URL_TEMPLATE = '/api/accounts/%s/users/%s';
    private const START_VERIFICATION_URL_TEMPLATE = '/api/accounts/%s/verification/start';
    private const CONFIRM_URL_TEMPLATE = '/api/accounts/%s/verification/confirm';

    private const GET_VERIFICATION_CODES_TEMPLATE = '/api/accounts/%s/verification/codes';
    private const DELETE_USER_URL_TEMPLATE = '/api/accounts/%s/users/%s';

    private const TOO_MANY_ATTEMPTS_STATUS_CODE = 429;
    private const BAD_REQUEST_STATUS_CODE = 400;
    // The verification service answers its send limit with 400 and this message, not with 429.
    private const TOO_MANY_ATTEMPTS_MESSAGE = 'Too many attempts';

    public function storeAccount(StoreAccountDTO $dto): void
    {
        $url = $this->generateStoreAccountUrl();
        $body = [
            'id' => $dto->getId(),
            'name' => $dto->getName(),
            'lang' => $dto->getLang(),
            'settings' => $dto->getSettings()->toArray(),
        ];
        try {
            $this->post($url, $body);
        } catch (ClientException) {
            return;
        }
    }

    public function findAccountSettings(string $accountId): ?VerificationAccountSettingsDTO
    {
        $url = $this->generateFindAccountUrl($accountId);
        try {
            $response = $this->get($url);
            $settings = $response['settings'] ?? [];
            return VerificationAccountSettingsDTO::fromArray($settings);
        } catch (ClientException) {
            return null;
        }
    }

    public function storeUser(UserDTO $dto): void
    {
        $url = $this->generateStoreUserUrl($dto->getAccountId());
        $body = [
            'id' => $dto->getId(),
            'phone' => $dto->getPhone(),
            'level' => $dto->getLevel(),
            'standardCode' => $dto->getStandardCode(),
        ];
        try {
            $this->post($url, $body);
        } catch (ClientException) {
            return;
        }
    }

    public function findUser(
        string $accountId,
        string $userId,
    ): ?UserDTO {
        $url = $this->generateFindUserUrl($accountId, $userId);
        try {
            $response = $this->get($url);
            return UserDTO::fromArray($response);
        } catch (ClientException) {
            return null;
        }
    }

    public function deleteUser(
        string $accountId,
        string $userId,
    ): void {
        $url = $this->generateDeleteUserUrl($accountId, $userId);
        try {
            $this->delete($url);
        } catch (ClientException) {
        }
    }

    /**
     * @param string $accountId
     * @param string $phone
     * @return StartVerificationResponseDTO
     * @throws TooManyVerificationAttempts
     * @throws GuzzleException
     */
    public function startVerification(
        string $accountId,
        string $phone,
    ): StartVerificationResponseDTO {
        $url = $this->generateStartVerificationUrl($accountId);
        $body = [
            'phone' => $phone,
        ];
        try {
            $response = $this->post($url, $body);
        } catch (VerificationHttpClientException $e) {
            if ($this->isTooManyAttemptsAnswer($e)) {
                throw new TooManyVerificationAttempts(
                    $e->getMessage(),
                    self::TOO_MANY_ATTEMPTS_STATUS_CODE,
                );
            }
        } catch (ClientException) {
            throw new RuntimeException('Invalid verification response');
        }
        return StartVerificationResponseDTO::fromArray($response ?? []);
    }

    /**
     * @throws VerificationCodeLockedException too many wrong codes, a new code has to be sent
     * @throws VerificationCodeException the code is wrong or the request failed
     * @throws GuzzleException
     */
    public function confirm(
        string $accountId,
        string $phone,
        string $code
    ): void {
        $url = $this->generateConfirmUrl($accountId);
        $body = [
            'phone' => $phone,
            'code' => $code,
        ];
        try {
            $this->post($url, $body);
        } catch (VerificationHttpClientException $e) {
            if ($this->isCodeLockedAnswer($e)) {
                throw new VerificationCodeLockedException(
                    $e->getMessage(),
                    $e->getCode(),
                );
            }
            throw new VerificationCodeException(
                $e->getMessage(),
                $e->getCode(),
            );
        }
    }

    public function searchVerificationCodes(
        string $accountId,
        CodesFiltersDTO $dto,
    ): CodesDTO {
        $url = $this->generateGetVerificationCodesUrl($accountId);

        return CodesDTO::fromArray($this->get($url, [
            'json' => $dto->toArray(),
        ]) ?? []);
    }

    private function isTooManyAttemptsAnswer(VerificationHttpClientException $exception): bool
    {
        if ($exception->getCode() === self::TOO_MANY_ATTEMPTS_STATUS_CODE) {
            return true;
        }
        if ($exception->getCode() !== self::BAD_REQUEST_STATUS_CODE) {
            return false;
        }

        return $exception->getResponseMessage() === self::TOO_MANY_ATTEMPTS_MESSAGE;
    }

    private function isCodeLockedAnswer(VerificationHttpClientException $exception): bool
    {
        return $exception->getResponseErrorCode() === VerificationCodeLockedException::ERROR_CODE;
    }

    private function generateStoreAccountUrl(): string
    {
        return self::STORE_ACCOUNT_URL_TEMPLATE;
    }

    private function generateFindAccountUrl(string $accountId): string
    {
        return sprintf(self::FIND_ACCOUNT_URL_TEMPLATE, $accountId);
    }

    private function generateStoreUserUrl(string $accountId): string
    {
        return sprintf(self::STORE_USER_URL_TEMPLATE, $accountId);
    }

    private function generateFindUserUrl(string $accountId, string $userId): string
    {
        return sprintf(self::FIND_USER_URL_TEMPLATE, $accountId, $userId);
    }

    private function generateDeleteUserUrl(string $accountId, string $userPhone): string
    {
        return sprintf(self::DELETE_USER_URL_TEMPLATE, $accountId, $userPhone);
    }

    private function generateStartVerificationUrl(string $accountId): string
    {
        return sprintf(self::START_VERIFICATION_URL_TEMPLATE, $accountId);
    }

    private function generateConfirmUrl(string $accountId): string
    {
        return sprintf(self::CONFIRM_URL_TEMPLATE, $accountId);
    }

    private function generateGetVerificationCodesUrl(string $accountId): string
    {
        return sprintf(self::GET_VERIFICATION_CODES_TEMPLATE, $accountId);
    }
}
