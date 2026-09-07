# DEV-18603 — send the internal gateway token (verification-api-sdk)

Branch: `feature-DEV-18603`

## What changed

The SDK now sends `X-Internal-Gateway-Token` on every request, so its consumers
keep working once the `verification` service starts requiring it.

- `src/App/HttpClient.php` — header constant + the token is read once and put into the client headers
- `src/config/verification-api-sdk.php` — new `token` key, read from `VERIFICATION_INTERNAL_GATEWAY_TOKEN`

The header constant is declared as an untyped `private const` to stay compatible
with the PHP versions this package still supports.

## Env variable

`VERIFICATION_INTERNAL_GATEWAY_TOKEN` — the same value the `verification` service is configured with.

## Release

This package must be tagged (minor bump) and the new version pulled into its
consumers: `livesite`. The existing caret constraints already accept a minor
bump, so only `composer.lock` changes there.

## Database / performance

No database access, no queries, no schema changes.

## Risk

If a consumer is deployed with an old SDK version after the service is closed,
its calls return 401. Release the SDK and update consumers **before** the
service is deployed.
