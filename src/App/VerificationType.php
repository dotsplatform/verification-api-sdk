<?php
/**
 * Description of VerificationTypes.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Oleksandr Polosmak <o.polosmak@dotsplatform.com>
 */

namespace Dotsplatform\Verification;

abstract class VerificationType
{
    public const SMS = 'sms';
    public const CALL = 'call';
    public const TELEGRAM_GATEWAY = 'telegram_gateway';
    public const CALL_LAST_FOUR_DIGITS = 'call-last-four-digits';
}