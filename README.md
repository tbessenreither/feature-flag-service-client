
# Feature Flag Service Client Bundle

A Symfony bundle providing a client for feature flag service, with HTTP API integration and caching.


## Version

Package: tbessenreither/feature-flag-service-client
Current version: unreleased


## Requirements

- PHP 8.4 or higher
- Symfony 7.3 or higher


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
    Tbessenreither\FeatureFlagServiceClient\Bundle\FeatureFlagClientBundle::class => ['all' => true],
];
```

### Environment Variables

Set the following environment variables in your `.env` or server config:

- `FFS_API_URL`   (API base URL)
- `FFS_SCOPE`     (API scope)
- `FFS_API_KEY`   (API key)



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
    ) {}

    public function doSomething(): void
    {
        // Feature flags are formatted as dot-separated paths: Service.Module.Feature
        if ($this->featureFlagClient->isEnabled('User.Auth.Login')) {
            // Feature is enabled
        }
    }
}
```


## API Reference

See [documentation/openapi_spec.json](documentation/openapi_spec.json) for the OpenAPI specification of the Feature Flag Service API.

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

Tests are written with PHPUnit 11 and located in the `tests/` directory.

To run tests:
```bash
ddev exec vendor/bin/phpunit
```
Or use DDEV tasks:
```bash
ddev test         # Runs all PHPUnit tests
ddev test --filter TestName   # Runs specific test
```



## License

MIT
