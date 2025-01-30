<?php
/**
 * Description of CodesFiltersDTO.php
 *
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dotsplatform\Verification\DTO;

use Dots\Data\DTO;

class CodesFiltersDTO extends DTO
{
    protected int $timeFrom;

    protected int $timeTo;

    protected ?int $status;

    protected ?string $type;

    protected ?string $phone;

    public function getTimeFrom(): int
    {
        return $this->timeFrom;
    }

    public function getTimeTo(): int
    {
        return $this->timeTo;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }
}
