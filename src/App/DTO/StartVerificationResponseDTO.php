<?php
/**
 * Description of StartVerificationResponseDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Oleksandr Polosmak <o.polosmak@dotsplatform.com>
 */

namespace Dotsplatform\Verification\DTO;


use Dots\Data\DTO;

class StartVerificationResponseDTO extends DTO
{
    protected ?string $verificationType = null;

    public function getVerificationType(): ?string
    {
        return $this->verificationType;
    }
}