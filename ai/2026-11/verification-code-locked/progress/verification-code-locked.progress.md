# Progress — verification-code-locked (SDK)

- [x] 1. Error body kept on `VerificationHttpClientException` (`getResponseBody()`, `getResponseMessage()`,
  `getResponseErrorCode()`); non-JSON / scalar bodies give `[]`.
- [x] 2. `startVerification()` maps 400 "Too many attempts" to `TooManyVerificationAttempts` (code 429).
- [x] 3. `VerificationCodeLockedException` thrown by `confirm()` for `CODE_LOCKED`.
- [x] 4. phpunit ^11.5 (require-dev), `phpunit.xml`, `tests/Unit` — 11 tests green.
- [x] 5. Review file written, branch pushed.

## Summary

Done. PHPStan reports only the 2 pre-existing "ignored pattern not matched" errors (same on master).
php-cs-fixer applied to the new exception files only; the rest of `src/` is not formatted on master either and was
left alone to keep the diff readable. Release: tag **v2.6.0** (new feature, backwards compatible).
