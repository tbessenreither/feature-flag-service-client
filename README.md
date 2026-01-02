# Feature Flag Service Bundle

A Symfony bundle providing a client for feature flag service.

## Requirements

- PHP 8.4 or higher
- Symfony 7.3 or higher

## Installation

Install the bundle using Composer:

```bash
composer require tbessenreither/feature-flag-service
```

## Configuration

Add the bundle to your `config/bundles.php` file:

```php
<?php

return [
    // ...
    Tbessenreither\FeatureFlagService\FeatureFlagServiceBundle::class => ['all' => true],
];
```

Configure the bundle in `config/packages/feature_flag_service.yaml`:

```yaml
feature_flag_service:
    api_url: 'http://localhost:8000'  # URL of your feature flag service
    api_key: null                      # Optional API key for authentication
    timeout: 5                         # Request timeout in seconds
    cache_enabled: true                # Enable caching of feature flags
    cache_ttl: 300                     # Cache TTL in seconds
```

## Usage

Inject the `FeatureFlagClientInterface` into your services:

```php
<?php

namespace App\Service;

use Tbessenreither\FeatureFlagService\Client\FeatureFlagClientInterface;

class MyService
{
    public function __construct(
        private readonly FeatureFlagClientInterface $featureFlagClient
    ) {
    }

    public function doSomething(): void
    {
        if ($this->featureFlagClient->isEnabled('my-feature')) {
            // Feature is enabled
        }
        
        $value = $this->featureFlagClient->getValue('my-feature');
        
        $allFlags = $this->featureFlagClient->getAllFlags();
    }
}
```

## Development

### Install dependencies

```bash
composer install
```

### Run tests

```bash
vendor/bin/phpunit
```

## License

MIT