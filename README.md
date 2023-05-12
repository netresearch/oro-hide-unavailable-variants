# Hide Unavailable Variants Bundle

This OroCommerce bundle hides attribute combinations of configurable products in the
storefront, if an applicable variant product does not exist.

## Installation

### Requirements
The Bundle is not synchronized with the ORO development release cycle, look at this
table to choose the right version to install:

| HideUnavailableVariantsBundle | ORO Commerce      |
|-------------------------------|-------------------|
| 1.0.*                         | >=5.0.0   <=5.0.8 |
| 2.0.*                         | >=5.0.9   <5.1.0  |
| 3.0.*                         | 5.1.*             |

### Composer Installation

```bash
$ composer require netresearch/oro-hide-unavailable-variants-bundle
```

## Features

- Rows and columns (for which no product variants exist) are removed from the
  inline matrix form for configurable products.
- Non-existent variants are removed from the selection box of configurable products.

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
