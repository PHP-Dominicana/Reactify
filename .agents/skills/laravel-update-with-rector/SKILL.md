---
name: laravel-update-with-rector
description: >
  Upgrades a Laravel application or package to a newer Laravel version using Rector and driftingly/rector-laravel.
  Inspects composer.json, detects current and target versions, configures Rector with the correct LaravelSetList
  or LaravelLevelSetList rulesets, updates dependencies conservatively, runs a dry-run first, and produces
  an upgrade checklist covering both automated code changes and manual skeleton/breaking-change steps.
  The latest stable Laravel version is Laravel 13 (March 2026). Activate for phrases like
  "upgrade to Laravel 12", "upgrade to Laravel 13", "migrate my Laravel app",
  "fix Laravel deprecation warnings", "help me with the Laravel upgrade guide",
  "set up Rector for Laravel upgrade", or any request to automate a Laravel version migration —
  even when Rector is not mentioned by name.
license: MIT
compatible_agents:
  - claude-code
  - cursor
  - windsurf
tags:
  - laravel
  - php
  - rector
  - upgrade
  - migration
metadata:
  author: fperdomo
  version: "1.0.0"
  installed_path: .codex/skills/laravel-update-with-rector
---

# Laravel Version Upgrade with Rector

You are helping upgrade a Laravel application or package to a newer Laravel version using Rector and the `driftingly/rector-laravel` community ruleset.

**Current stable Laravel version: Laravel 13** (released March 2026). Default to this when the user hasn't specified a target.

## PHP Version Requirements by Laravel Version

| Laravel | Minimum PHP | Supported PHP |
|---|---|---|
| Laravel 10 | PHP 8.1 | 8.1 – 8.3 |
| Laravel 11 | PHP 8.2 | 8.2 – 8.4 |
| Laravel 12 | PHP 8.2 | 8.2 – 8.5 |
| Laravel 13 | **PHP 8.3** | 8.3 – 8.5 |

**Important:** Laravel 13 drops PHP 8.2 support entirely. If the project is on PHP 8.2, a PHP upgrade to 8.3+ is required before or alongside the Laravel 13 upgrade.

Always check PHP version first — if the project needs a PHP upgrade too, use the `php-update-with-rector` skill in tandem.

## Laravel 13 Full Server Requirements

When upgrading to Laravel 13, verify the environment meets these requirements:

**PHP extensions required:**
BCMath, Ctype, cURL, DOM, Fileinfo, Filter, Hash, JSON, Mbstring, OpenSSL, PCRE, PDO, Session, Tokenizer, XML

**Minimum database versions:**

| Database | Minimum version |
|---|---|
| MySQL | 5.7 |
| MariaDB | 10.3 |
| PostgreSQL | 10.0 |
| SQLite | 3.26.0 |
| SQL Server | 2017 |

**Composer:** 2.x required

**Key dependency changes (L12 → L13):**

| Package | Laravel 12 | Laravel 13 |
|---|---|---|
| `phpunit/phpunit` | ^11.0 | ^12.5 |
| `pestphp/pest` | ^3.0 | ^4.0 |
| `laravel/tinker` | ^2.0 | ^3.0 |
| `nunomaduro/collision` | ^8.0 | ^8.6 |
| `nesbot/carbon` | ^3.0 | ^3.0 (unchanged) |

Flag any of these in `composer.json` when upgrading — they need aligned upgrades.

## Core Goals

1. Detect current and target Laravel versions from the codebase
2. Choose a safe upgrade strategy — one major version at a time for large jumps
3. Configure Rector with the right `LaravelSetList` or `LaravelLevelSetList` constants
4. Execute safely: dry-run first, then apply
5. Deliver a complete upgrade checklist including breaking changes and manual steps Rector can't automate

---

## Step 1: Inspect the Project

Read these files before proposing anything:

- `composer.json` — Laravel version constraint, PHP constraint, dependencies
- `composer.lock` — actual installed `laravel/framework` version
- `vendor/composer/installed.json` — if available, for precise package versions
- `rector.php` or `rector.php.dist` — existing Rector config if present
- `phpunit.xml*` — test framework
- `phpstan.neon*` or `larastan` config — static analysis
- `bootstrap/app.php` — detect L11+ skeleton vs L10-style
- `app/Http/Kernel.php` — presence indicates pre-L11 skeleton

Determine:
- Current Laravel version (e.g., `"laravel/framework": "^11.0"`)
- Current PHP constraint
- Project type: **application** vs **package** (packages need more conservative treatment)
- Test suite presence
- Ecosystem packages that may need aligned upgrades (see Step 3)

---

## Step 2: Decide Target Version

- Default to the latest stable: **Laravel 13**
- If the user specifies a version, use it
- For multi-version jumps (e.g., L10 → L13), recommend one major at a time: L10 → L11 → L12 → L13
- Always note that official Laravel upgrade guides must be checked alongside Rector for each major jump

---

## Step 3: Composer Dependency Plan

```bash
composer require rector/rector --dev
composer require driftingly/rector-laravel --dev
```

Update the Laravel constraint in `composer.json` to match the target version:

```json
"require": {
    "laravel/framework": "^13.0"
}
```

Check for blocking dependencies before updating:

```bash
composer why-not laravel/framework ^13.0
```

Ecosystem packages that often need aligned major version upgrades:

- **Livewire** (v2 → v3 for L10+)
- **Filament** (v2 → v3 for L10+)
- **Laravel Sanctum**
- **Laravel Passport**
- **Laravel Cashier**
- **Laravel Telescope**
- **Laravel Horizon**
- **Pest / PHPUnit** (PHPUnit 11 for L11+)
- **Larastan / PHPStan**
- **Carbon** (Carbon 3 required for L12+)

Flag any of these found in `composer.json` and recommend checking each package's own upgrade guide.

---

## Step 4: Configure `rector.php`

Generate bootstrap if none exists:

```bash
vendor/bin/rector init
```

### Option A: Single-version set (recommended for small jumps)

Use `LaravelSetList` to apply rules for one specific version:

```php
use Rector\Config\RectorConfig;
use RectorLaravel\Set\LaravelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/config',
        __DIR__ . '/tests',
    ])
    ->withSkip([
        __DIR__ . '/vendor',
        __DIR__ . '/storage',
        __DIR__ . '/bootstrap/cache',
        __DIR__ . '/public',
    ])
    ->withSets([
        LaravelSetList::LARAVEL_130, // adjust to target
    ]);
```

### Available `LaravelSetList` constants (one version at a time, NOT cumulative)

| Constant | Target |
|---|---|
| `LaravelSetList::LARAVEL_90` | Laravel 9 |
| `LaravelSetList::LARAVEL_100` | Laravel 10 |
| `LaravelSetList::LARAVEL_110` | Laravel 11 |
| `LaravelSetList::LARAVEL_120` | Laravel 12 |
| `LaravelSetList::LARAVEL_130` | Laravel 13 |

### Option B: Cumulative set (recommended for multi-version jumps)

Use `LaravelLevelSetList` to apply all rules up to a target version in one pass:

```php
use RectorLaravel\Set\LaravelLevelSetList;

->withSets([
    LaravelLevelSetList::UP_TO_LARAVEL_130, // applies L9 through L13 rules cumulatively
])
```

Available: `UP_TO_LARAVEL_90`, `UP_TO_LARAVEL_100`, `UP_TO_LARAVEL_110`, `UP_TO_LARAVEL_120`, `UP_TO_LARAVEL_130`.

For large jumps, cumulative sets are convenient but produce larger diffs — use individual sets with commits between each for more reviewable changes.

### Multi-version jump example (L10 → L13, step by step)

```php
// Pass 1: apply in rector.php, then commit
->withSets([LaravelSetList::LARAVEL_110])

// Pass 2
->withSets([LaravelSetList::LARAVEL_120])

// Pass 3
->withSets([LaravelSetList::LARAVEL_130])
```

### Combined PHP + Laravel upgrade

If also upgrading PHP at the same time:

```php
use Rector\Set\ValueObject\LevelSetList;

->withSets([
    LevelSetList::UP_TO_PHP_84,          // PHP rules first
    LaravelLevelSetList::UP_TO_LARAVEL_130, // then Laravel rules
])
```

### For Laravel packages (be conservative)

```php
->withPaths([
    __DIR__ . '/src',
    __DIR__ . '/tests',
])
// Do NOT include app/, database/, routes/ — those are application-only paths
```

---

## Step 5: Safe Execution Sequence

```bash
# 1. Install Rector + Laravel ruleset
composer require rector/rector --dev
composer require driftingly/rector-laravel --dev

# 2. Dry-run — preview only, no writes
vendor/bin/rector process --dry-run

# 3. Review the diff, adjust rector.php if needed, repeat dry-run

# 4. Apply changes
vendor/bin/rector process

# 5. Clear caches
php artisan config:clear && php artisan route:clear && php artisan cache:clear

# 6. Quick sanity checks
php artisan route:list
php artisan config:show app

# 7. Format (if Pint configured)
vendor/bin/pint

# 8. Static analysis (if Larastan configured)
vendor/bin/phpstan analyse

# 9. Run tests
composer test

# 10. Check official upgrade guide for manual steps
```

Commit after each logical batch:

```bash
git add -A && git commit -m "chore: apply Rector Laravel 13 upgrade rules"
```

---

## Step 6: Breaking Changes & Manual Steps by Version

Rector handles **code-level** changes automatically. These are the **manual** items to include in the upgrade checklist for each version.

### Laravel 13 (L12 → L13)

Rector rules applied by `LARAVEL_130`: *(check `driftingly/rector-laravel` changelog for current rule list)*

Manual checks:
- Review official guide: https://laravel.com/docs/13.x/upgrade

### Laravel 12 (L11 → L12)

Rector rules applied by `LARAVEL_120`:
- `ContainerBindConcreteWithClosureOnlyRector` — container binding pattern changes
- `ScopeNamedClassMethodToScopeAttributedClassMethodRector` — scope methods converted to `#[Scope]` attributes
- `RenameMethodRector` — `Request::get()` renamed to `Request::input()`

Manual checks:
- **Carbon 3 required** — remove Carbon 2 from dependencies; update any Carbon 2-specific API usage
- **`HasUuids` now generates UUIDv7** — if you rely on UUIDv4 ordering, add `HasVersion4Uuids` trait
- **Database Grammar constructor** — now requires `Connection` instance; affects custom database classes
- **`DatabaseTokenRepository`** — `$expires` constructor arg now in seconds (not minutes)
- **Storage local disk** — default root changed from `storage/app` to `storage/app/private`
- **Image validation** — SVG excluded by default; use `File::image(allowSvg: true)` to re-allow
- **`Concurrency::run()`** — results now preserve associative array keys
- **Route precedence** — same-name routes now match first registered (not last)

Official guide: https://laravel.com/docs/12.x/upgrade

### Laravel 11 (L10 → L11)

Rector rules applied by `LARAVEL_110`: method renames, deprecated patterns, updated signatures.

Manual checks (skeleton restructuring — optional but recommended):
- **`bootstrap/app.php`** — new `Application::configure()` format; middleware registered here
- **`app/Http/Kernel.php` removed** — middleware moves to `bootstrap/app.php`
- **`app/Console/Kernel.php` removed** — scheduled tasks move to `routes/console.php`
- **`app/Exceptions/Handler.php` removed** — exception handling in `bootstrap/app.php`
- **Service providers consolidated** — many default providers removed; `AppServiceProvider` takes over

Official guide: https://laravel.com/docs/11.x/upgrade

### Laravel 10 (L9 → L10)

- `$dates` property on models deprecated — migrate to `$casts`
- Middleware method signatures updated
- Carbon 3 compatibility recommended

Official guide: https://laravel.com/docs/10.x/upgrade

---

## Step 7: Deliverables

Always provide:

1. **Composer change proposal** — updated PHP + Laravel constraints, dev deps, flagged ecosystem packages
2. **`rector.php` config** — ready to use with correct `LaravelSetList` or `LaravelLevelSetList`
3. **Exact commands** — copy-paste ready in correct order
4. **Upgrade checklist** — Rector-automated items + manual breaking change items per version
5. **Risks / manual review points** — ecosystem packages that may block the upgrade

---

## Common Errors

| Error | Fix |
|---|---|
| `Class RectorLaravel\Set\LaravelSetList not found` | `composer require driftingly/rector-laravel --dev` |
| `LARAVEL_120` or `LARAVEL_130` constant not found | `composer update driftingly/rector-laravel` to get the latest version |
| Composer conflict after `composer update` | `composer why-not laravel/framework ^13.0` |
| App boots but routes return 500 | Check service providers and middleware registration (L11+ skeleton) |
| Rector makes no changes | Verify `withPaths()` points to actual app directories |
| Tests fail after apply | Revert specific file with `git checkout`, add rule to `withSkip()`, re-run |
| Carbon errors after L12 upgrade | Update Carbon to v3: `composer require nesbot/carbon:^3.0` |