<?php
/**
 * Description of RikkiComDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Oleksandr Polosmak <o.polosmak@dotsplatform.com>
 */

namespace Dotsplatform\Verification\DTO;


use Dots\Data\DTO;

class RikkiComDTO extends DTO
{
    protected ?string $login = null;
    protected ?string $password = null;

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}