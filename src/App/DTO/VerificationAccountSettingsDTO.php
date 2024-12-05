<?php
/**
 * Description of VerificationAccountSettingsDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Oleksandr Polosmak <o.polosmak@dotsplatform.com>
 */

namespace Dotsplatform\Verification\DTO;

use Dots\Data\DTO;

class VerificationAccountSettingsDTO extends DTO
{
    protected string $verificationType;
    protected RikkiComDTO $rikki;
    protected TelegramGatewayAccountSettingsDTO $telegram_gateway;

    public static function fromArray(array $data): static
    {
        $data['telegram_gateway'] = TelegramGatewayAccountSettingsDTO::fromArray($data['telegram_gateway'] ?? []);
        return parent::fromArray($data);
    }

    public function getRikki(): RikkiComDTO
    {
        return $this->rikki;
    }

    public function getTelegramGateway(): TelegramGatewayAccountSettingsDTO
    {
        return $this->telegram_gateway;
    }

    public function getVerificationType(): string
    {
        return $this->verificationType;
    }
}
