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

    public function getVerificationType(): string
    {
        return $this->verificationType;
    }
}