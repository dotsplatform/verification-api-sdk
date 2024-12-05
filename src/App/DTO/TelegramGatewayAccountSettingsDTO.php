<?php
/**
 * Description of TelegramAccountSettingsDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Yehor Herasymchuk <yehor@dotsplatform.com>
 */

namespace Dotsplatform\Verification\DTO;

use Dots\Data\DTO;

class TelegramGatewayAccountSettingsDTO extends DTO
{
    protected ?string $access_token;

    protected ?string $sender_name;

    protected ?string $url;

    public function getAccessToken(): ?string
    {
        return $this->access_token;
    }

    public function getSenderName(): ?string
    {
        return $this->sender_name;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }


}
