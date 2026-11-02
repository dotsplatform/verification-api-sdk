# Task: verification SDK — "too many attempts" on 400 and a locked code on confirm

- `startVerification()`: the verification service answers its send limit with **400** `{"message":"Too many attempts"}`,
  not 429. The SDK swallowed it and returned an empty (SMS) answer, so the app said "code sent" while nothing was sent.
  Throw `TooManyVerificationAttempts` (code 429) for it as well; keep 429 handling.
- `confirm()`: the service (branch `feat-sms-daily-cap-and-locked-code`) adds `error_code` (`INVALID_CODE`, `CODE_LOCKED`)
  to the 400 answer. Throw `VerificationCodeLockedException extends VerificationCodeException` for `CODE_LOCKED`.
  A body without `error_code` behaves exactly as before.
- Tests for both. Branch `feat-too-many-on-400-and-code-locked`, push, no tag.
