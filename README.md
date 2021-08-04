[![License](https://img.shields.io/packagist/l/synolia/sylius-akeneo-plugin.svg)](LICENCE)
![Tests](https://github.com/synolia/OroCommerceFavoritePlugin/workflows/CI/badge.svg?branch=master)
[Version] TODO
[Total Downloads] TODO

# Oro Favorite Bundle
This plugin allows you to save products as favorite in OroCommerce

## Features

* Enable or Disable the Plugin in the BO - [Documentation](docs/ENABLED.md)
* Save product as Favorite through a Heart icon - [Documentation](docs/MARK.md)
* Display within the Customer account a list of their favorite products with the possibility to add them to a shopping list - [Documentation](docs/LIST.md)

## Requirements

| | Version |
| :--- | :--- |
| PHP  | 7.4 |
| OroCommerce | 4.2 |

## Installation

1. Install the plugin using composer:
```shell
composer require synolia/orocommerce-favorite-plugin
```
2. Run the migrations
```shell
bin/console oro:migration:load --force
```
3. Clear cache
```shell
bin/console cache:clear
```

## Contributing

* See [How to contribute](CONTRIBUTING.md)

## License

This library is under the [EUPL-1.2 license](LICENSE).

## Credits

Developed by [Synolia](https://synolia.com/).