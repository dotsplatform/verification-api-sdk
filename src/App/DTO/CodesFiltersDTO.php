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
    protected int $dateFrom;

    protected int $dateTo;

    protected ?int $status;

    protected ?string $type;

    protected ?string $phone;

    protected int $limit = 50;

    protected int $offset = 0;

    public function getDateFrom(): int
    {
        return $this->dateFrom;
    }

    public function getDateTo(): int
    {
        return $this->dateTo;
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

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}
