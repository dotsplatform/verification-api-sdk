<?php
/**
 * Description of VerificationTypes.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Oleksandr Polosmak <o.polosmak@dotsplatform.com>
 */

namespace DotsPlatform\Verification;


abstract class VerificationType
{
    public const SMS = 'sms';
    public const CALL = 'call';
}