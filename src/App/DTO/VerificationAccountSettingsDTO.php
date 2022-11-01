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

    public static function fromArray(array $data): static
    {
        $data['rikki'] = RikkiComDTO::fromArray($data['rikki'] ?? []);
        return parent::fromArray($data);
    }

    public function getRikki(): RikkiComDTO
    {
        return $this->rikki;
    }

    public function getVerificationType(): string
    {
        return $this->verificationType;
    }
}