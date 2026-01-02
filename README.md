# Feature Flag Service Bundle

A Symfony bundle providing a client for feature flag service.

## Version

Package: tbessenreither/feature-flag-service
Current version: unreleased

## Requirements

- PHP 8.4 or higher
- Symfony 7.4 or higher

## Installation

To install this bundle using Composer, add the repository to your composer.json:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/tbessenreither/feature-flag-service-client"
        }
    ]
}
```

Then require the bundle:

```bash
composer require tbessenreither/feature-flag-service-client
```

## Configuration

Add the bundle to your `config/bundles.php` file:

```php
<?php

return [
    // ...
    Tbessenreither\FeatureFlagClientBundle\Bundle\FeatureFlagServiceBundle::class => ['all' => true],
];
```


## Usage

Inject the `FeatureFlagClientInterface` into your services:

```php
<?php

namespace App\Service;

use Tbessenreither\FeatureFlagServiceClient\Interface\FeatureFlagClientInterface;

class MyService
{
    public function __construct(
        private readonly FeatureFlagClientInterface $featureFlagClient
    ) {
    }

    public function doSomething(): void
    {
        // Feature flags are formatted as dot-separated paths: Service.Module.Feature
        if ($this->featureFlagClient->lookup('User.Auth.Login')) {
            // Feature is enabled
        }
    }
}
```

## Development

### Using DDEV (Recommended)

This project includes a DDEV configuration for local development with PHP 8.4.

1. Start the environment:
    ```bash
    ddev start
    ```

2. Run composer install:
    ```bash
    ddev composer install
    ```

See [.ddev/README.md](.ddev/README.md) for more details.

## Testing

To run tests (optional, not required for startup):
```bash
ddev test         # Runs all PHPUnit tests
ddev test --filter TestName   # Runs specific test
```


## License

MIT