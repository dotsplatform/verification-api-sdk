# verification-code-locked — verification-api-sdk

Branch: `feat-too-many-on-400-and-code-locked`

## What changed

- `src/App/Exception/VerificationHttpClientException.php` — optional 4th constructor argument `responseBody`
  (decoded JSON of the failed answer) + `getResponseBody()`, `getResponseMessage()`, `getResponseErrorCode()`.
  The first three constructor arguments are the same as `Exception`'s, so existing `new` calls keep working.
- `src/App/HttpClient.php` — `parseResponseStatus()` passes the decoded error body. Decoding never fails
  (non-JSON or scalar body → `[]`).
- `src/App/VerificationClient.php`
  - `startVerification()`: 429 **or** 400 with `message === "Too many attempts"` → `TooManyVerificationAttempts`,
    always with code 429 (consumers check that code).
  - `confirm()`: `error_code === "CODE_LOCKED"` → new `VerificationCodeLockedException`; everything else → the same
    `VerificationCodeException` (same message and code) as before.
- `src/App/Exception/VerificationCodeLockedException.php` — new, extends `VerificationCodeException`, so every
  existing `catch (VerificationCodeException)` still catches it.
- Tests: phpunit ^11.5 in require-dev (lock updated, dev packages only), `phpunit.xml`, `tests/Unit/*` (11 tests).

## Still swallowed by `startVerification()` (unchanged on purpose)

Any other 4xx answer — e.g. 400 "User is banned", 401 (gateway token), 404 (unknown account), 422 (validation) —
still returns the default `StartVerificationResponseDTO` (type `sms`), so the caller reports "code sent".
5xx answers are not swallowed: Guzzle throws `ServerException` (unchanged).

## Database / migrations / breaking changes

None. No database access. Not a breaking change: new exception is a subclass, `TooManyVerificationAttempts` keeps
code 429. Behaviour change for consumers: a 400 "Too many attempts" now throws instead of returning silently —
that is the fix.

## Performance

No queries, no extra requests. The error body is decoded once per failed request (low).

## Release

Tag **v2.6.0** (last tag v2.5.1). Consumers with `^2.5` pick it up with
`composer update dotsplatform/verification-api-sdk`.
