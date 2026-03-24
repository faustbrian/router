# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Initial release
- Added repository-level maintainer guidance in `AGENTS.md`.
- Expanded PHPDoc coverage across the route registrar, service provider,
  and route attribute classes to match the higher-detail package
  documentation standard used in sibling `cline` packages.

### Changed
- Renamed the internal route marker interfaces to
  `RouteAttributeInterface` and `WhereAttributeInterface` so the package
  follows the repository interface naming rule consistently.

### Fixed
- Tightened router attribute and registrar type declarations so the full
  `just test` suite passes PHPStan again.
