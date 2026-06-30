# Bites Identity
Filament authentication package with:
- Wizard login (Username → Password → Face Verification)
- Native Filament TOTP (for password reset & MFA)
- Separate `user_auth` table — no changes to default `users` table
- Forced setup flow
- Works fully on-prem / offline phone

---

## Installation
1. Extract into `packages/bites/identity`
2. Add to root `composer.json`:
```json
"repositories": [
    { "type": "path", "url": "./packages/bites/identity" }
],
"require": {
    "bites/identity": "@dev"
}