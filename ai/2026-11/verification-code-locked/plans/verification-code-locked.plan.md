# Plan — verification-code-locked (SDK)

1. Keep the decoded error body on `VerificationHttpClientException` (new optional constructor argument, getters for
   `message` and `error_code`); `HttpClient::parseResponseStatus()` passes it, decoding never fails.
2. `startVerification()`: 429, or 400 with message "Too many attempts" → `TooManyVerificationAttempts` with code 429.
3. New `VerificationCodeLockedException extends VerificationCodeException`; `confirm()` throws it for `error_code` CODE_LOCKED.
4. Add phpunit (dev), a fake-HTTP client (Guzzle MockHandler) and unit tests for start and confirm.
5. Review file, commit, push.
