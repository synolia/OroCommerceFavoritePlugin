[![License](https://img.shields.io/packagist/l/synolia/sylius-akeneo-plugin.svg)](LICENCE)
![Tests](https://github.com/synolia/OroCommerceFavoritePlugin/workflows/CI/badge.svg?branch=master)
[![Version](http://poser.pugx.org/synolia/orocommerce-favorite-plugin/v)](https://packagist.org/packages/synolia/orocommerce-favorite-plugin)
[![Total Downloads](http://poser.pugx.org/synolia/orocommerce-favorite-plugin/downloads)](https://packagist.org/packages/synolia/orocommerce-favorite-plugin)

# Oro Favorite Bundle
This plugin allows you to save products as favorite in OroCommerce

## Features

* Enable or Disable the Plugin in the BO - [Documentation](docs/ENABLED.md)
* Save product as Favorite through a Heart icon - [Documentation](docs/MARK.md)
* Display within the Customer account a list of their favorite products with the possibility to add them to a shopping list - [Documentation](docs/LIST.md)

## Requirements

| | Version |
| :--- | :--- |
| PHP  | 7.4, 8.0 |
| OroCommerce | 4.2 |

## Installation

1. Install the Plugin using Composer:
```shell
composer require synolia/orocommerce-stock-alert-plugin
```
2. Run the Migrations
```shell
bin/console oro:migration:load --force
```
3. Clear Cache
```shell
bin/console cache:clear
```
4. Install & Build the Assets
```shell
bin/console oro:assets:install --symlink
```

## Contributing

* See [How to contribute](CONTRIBUTING.md)

## License

This library is under the [EUPL-1.2 license](LICENSE).

## Credits

Developed by [Synolia](https://synolia.com/).
