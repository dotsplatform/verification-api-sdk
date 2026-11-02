<?php
/**
 * Description of VerificationCodeLockedException.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dotsplatform\Verification\Exception;

/**
 * The verification service locked confirmation for this phone after too many wrong codes:
 * even the right code is rejected until a new code is sent.
 * Extends VerificationCodeException, so every existing catch of a wrong code keeps working.
 */
class VerificationCodeLockedException extends VerificationCodeException
{
    public const ERROR_CODE = 'CODE_LOCKED';
}
