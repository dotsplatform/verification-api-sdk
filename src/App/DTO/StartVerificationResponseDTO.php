<?php
/**
 * Description of StartVerificationResponseDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Oleksandr Polosmak <o.polosmak@dotsplatform.com>
 */

namespace Dotsplatform\Verification\DTO;

use Dots\Data\DTO;
use Dotsplatform\Verification\VerificationType;

class StartVerificationResponseDTO extends DTO
{
    protected string $verificationType = VerificationType::SMS;

    public function getVerificationType(): string
    {
        return $this->verificationType;
    }
}
