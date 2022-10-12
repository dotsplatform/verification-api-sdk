<?php
/**
 * Description of PhoneDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Oleksandr Polosmak <o.polosmak@dotsplatform.com>
 */

namespace Dotsplatform\Verification\DTO;

use Dots\Data\DTO;

class UserDTO extends DTO
{
    protected string $id;
    protected string $accountId;
    protected string $phone;
    protected int $level;
    protected int $standardCode;

    public function getId(): string
    {
        return $this->id;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getStandardCode(): int
    {
        return $this->standardCode;
    }
}
