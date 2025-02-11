<?php
/**
 * Description of TelegramAccountSettingsDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\Verification\DTO;

use Dots\Data\DTO;
use Dotsplatform\Verification\VerificationType;

class FallbackSettingsDTO extends DTO
{
    protected bool $sendFallback = false;

    protected string $fallbackVerificationType = VerificationType::SMS;

    protected ?int $sendFallbackTimeout;

    public function isSendFallback(): bool
    {
        return $this->sendFallback;
    }

    public function getFallbackVerificationType(): string
    {
        return $this->fallbackVerificationType;
    }

    public function getSendFallbackTimeout(): ?int
    {
        return $this->sendFallbackTimeout;
    }
}
