<?php
/**
 * Description of StoreAccountDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Oleksandr Polosmak <o.polosmak@dotsplatform.com>
 */

namespace MA\App\Verification\DTO;


use Dots\Data\DTO;

class StoreAccountDTO extends DTO
{
    protected string $id;
    protected string $name;
    protected string $lang;
    protected VerificationAccountSettingsDTO $settings;

    public static function fromArray(array $data): static
    {
        $data['settings'] = VerificationAccountSettingsDTO::fromArray($data['settings'] ?? []);
        return parent::fromArray($data);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function getSettings(): VerificationAccountSettingsDTO
    {
        return $this->settings;
    }
}