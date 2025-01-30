<?php
/**
 * Description of VerificationClient.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Oleksandr Polosmak <o.polosmak@dotsplatform.com>
 */

namespace Dotsplatform\Verification;

use Dotsplatform\Verification\DTO\CodesFiltersDTO;
use Dotsplatform\Verification\DTO\StartVerificationResponseDTO;
use Dotsplatform\Verification\DTO\UserDTO;
use GuzzleHttp\Exception\ClientException;
use Dotsplatform\Verification\DTO\StoreAccountDTO;
use Dotsplatform\Verification\DTO\VerificationAccountSettingsDTO;
use Dotsplatform\Verification\Exception\TooManyVerificationAttempts;
use Dotsplatform\Verification\Exception\VerificationCodeException;
use Dotsplatform\Verification\Exception\VerificationHttpClientException;
use GuzzleHttp\Exception\GuzzleException;
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
            if ($e->getCode() === 429) {
                throw new TooManyVerificationAttempts(
                    $e->getMessage(),
                    $e->getCode(),
                );
            }
        } catch (ClientException) {
            throw new RuntimeException('Invalid verification response');
        }
        return StartVerificationResponseDTO::fromArray($response ?? []);
    }

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
            throw new VerificationCodeException(
                $e->getMessage(),
                $e->getCode(),
            );
        }
    }

    public function searchVerificationCodes(
        string $accountId,
        CodesFiltersDTO $dto,
    ): array {
        $url = $this->generateConfirmUrl($accountId);
        $params = $dto->toArray();

        return $this->get($url, $params);
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
