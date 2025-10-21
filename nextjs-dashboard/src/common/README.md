Token storage

- Access token: stored in volatile in-memory variable via `setVolatileAccessToken` / `getAccessToken` / `clearVolatileAccessToken`.
  - Pros: reduces risk of token leakage from persistent storage.
  - Cons: token is lost on page reload, tab close or browser close. Use short session flows or use refresh token to reauthenticate.

- Refresh token: stored persistently in `localStorage` via `setRefreshToken` / `getRefreshToken` / `removeRefreshToken`.
  - Used by the API client to refresh access token when expired.

Usage patterns
- After successful login: call `setVolatileAccessToken(accessToken)` and `setRefreshToken(refreshToken)`.
- The Axios client (`src/common/api/client.ts`) will read `getAccessToken()` and attach `Authorization` header automatically for requests that require auth.
- On logout: call `clearTokens()` to clear both volatile access token and persistent refresh token.

Security notes
- This approach trades persistence for improved safety of the access token. If you need the user to remain logged-in across reloads, you'll need to support restoring access token from a secure persistent store (HTTP-only cookie or secure storage) and adjust logic accordingly.
