# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).


## [1.0.0] - 2023-05-16

### Added

- New bundle that removes rows and columns (for which no product variants exist) from
  the inline matrix form for configurable products.
  Non-existent variants are also removed from the selection box if this is configured
  (instead of the inline matrix form) in the backend.
