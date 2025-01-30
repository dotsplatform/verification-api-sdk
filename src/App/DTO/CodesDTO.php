<?php
/**
 * Description of CodesDTO.php
 *
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dotsplatform\Verification\DTO;

use Illuminate\Support\Collection;

class CodesDTO extends Collection
{
    public static function fromArray(array $data): static
    {
        return new static(array_map(
            fn (array $item) => CodeDTO::fromArray($item),
            $data
        ));
    }
}
