# DDEV Configuration

This project uses DDEV for local development environment.

## Requirements

- [DDEV](https://ddev.readthedocs.io/) installed on your system

## Getting Started

1. Start the DDEV environment:
   ```bash
   ddev start
   ```

2. Install dependencies (automatically runs on `ddev start`, but can be run manually):
   ```bash
   ddev composer install
   ```

3. Run tests:
   ```bash
   ddev test
   ```

## Available Commands

- `ddev test` - Run PHPUnit tests
- `ddev test --filter TestName` - Run specific tests
- `ddev composer [args]` - Run composer commands
- `ddev ssh` - SSH into the web container
- `ddev stop` - Stop the environment
- `ddev restart` - Restart the environment

## Configuration

The DDEV environment is configured with:
- PHP 8.4
- Composer 2
- No database (not needed for this bundle)
- Xdebug disabled by default

To enable Xdebug, run:
```bash
ddev xdebug on
```
