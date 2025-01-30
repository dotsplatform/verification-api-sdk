<?php
/**
 * Description of StoreCodeDTO.php
 *
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Oleksandr Polosmak <o.polosmak@dotsplatform.com>
 */

namespace Dotsplatform\Verification\DTO;

use Dots\Data\DTO;
use Illuminate\Support\Str;

class CodeDTO extends DTO
{
    protected string $id;

    protected string $accountId;

    protected string $code;

    protected string $phone;

    protected int $status;

    protected string $type;

    protected int $expireTime;

    public static function fromArray(array $data): static
    {
        $data['id'] = $data['id'] ?? Str::orderedUuid();
        return parent::fromArray($data);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getExpireTime(): int
    {
        return $this->expireTime;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }
}
