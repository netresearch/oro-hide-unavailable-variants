# Hide Unavailable Variants Bundle

This OroCommerce bundle hides attribute combinations of configurable products in the
storefront, if an applicable variant product does not exist.

## Installation

```bash
$ composer require netresearch/oro-hide-unavailable-variants-bundle
```

## Development

### Code Style and Static Analysis

Use the following commands to ensure the source code conforms to our **coding standards**
and guidelines:

* `composer phpcs` to check PHP related files against the PSR-12 code style
* `composer rector` to automatically comply with coding standards, simplify and improve
   code, and perform migrations (rules defined in `./rector.php`)
* `composer phpstan` for type and bug checking

Run the command `composer analysis` to run all commands at once. (That's exactly what
our CI does).
