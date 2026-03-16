# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

PHP SDK for the Russian Post (Почта России) API. Targets PHP 8.2.

## Commands

Once `composer.json` is created, standard commands will be:

```bash
composer install          # Install dependencies
composer test             # Run tests (configure in composer.json scripts)
vendor/bin/phpunit        # Run PHPUnit tests directly
vendor/bin/phpunit --filter TestName  # Run a single test
vendor/bin/phpcs          # Run code style checks (if PHP_CodeSniffer added)
```

## Notes

- The SDK has not been scaffolded yet. If `source_doc/` exists, it contains raw API documentation from the provider's website for reference during initial development.

