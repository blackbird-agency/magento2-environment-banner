# Environment banner &nbsp; [![Latest Stable Version](https://img.shields.io/badge/version-1.0.0-violet)](https://packagist.org/packages/blackbird/module-environment-banner) [![License: MIT](https://img.shields.io/github/license/blackbird-agency/magento2-environment-banner?logo=github&color=salmon)](./LICENSE) [![Download count](https://img.shields.io/packagist/dt/blackbird/module-environment-banner?color=aqua)]()

Magento 2 extension that provides a quick visual indication of the environment (production, staging, or development) you are currently in, with banners displayed on both the frontend and admin areas.

## Why choose this extension?

This module offers a convenient solution for teams working on Magento projects to easily identify the environment they are working in, helping to prevent any accidental changes or actions in production environments. With clear banners displayed prominently, developers, testers, and administrators can confidently navigate through different environments without confusion.

## Features

- Visual banners displayed on frontend and admin areas.
- Clearly distinguish between production, staging, and development environments.
- Easy setup and configuration.

## Installation

1. Install via Composer

```bash
composer require blackbird/module-environment-banner
```

2. Enable the module

```bash
php bin/magento setup:upgrade
```

3. Configure the extension in the Magento admin panel under `Stores > Configuration > Advanced > Environment Banner`. Fill the environment mapping.

4. Add the environment to the `app/etc/env.php` file. **The value should match one from the mapping.**

```php
[
    'system' => [
        'default' => [
            'environment' => 'development',
        ],
    ],
];
```

## About this fork

This extension is a fork of the original [c3limited/magento2-environment-banner](https://github.com/c3limited/magento2-environment-banner) module. While the original module provided valuable functionality, it had limitations such as compatibility only with Apache servers and absence from Packagist. In our fork, we have addressed these limitations and made enhancements for broader compatibility, including support for Nginx and other web servers.

**Notably, instead of relying on server configuration, as in the original module, our fork utilizes the `env.php` file for environment configuration, offering greater flexibility and ease of use.**
