# Changelog

## [Unreleased]

## [1.0.4]
### Added
- 99% code coverage for better development and release process
- this CHANGELOG

### Changed
- Added more typehints
- Replace typehints using Abstract classes with interfaces

### Removed
- Remove old Guzzle support

## [1.0.3] - 2018-04-29
### Added
- SearchResult model
- Easy to use SearchResult->getSuccessfulResults that only return valid data

### Changed
- **PHP 7.1 is now the main (and minimal) target version**
- Replaced _Result_ model with _ServiceResult_

### Removed
- **PHP 5.6 Support removed**

## [1.0.2] - 2018-04-28
### Added
- Error handling on responses from remote services
  - Results now contain an isError() method to check if lookup has succeeded
- RequestError model
- Result model

## [1.0.1] - 2018-04-02
### Added
- Forked from [PHPMusicInfo]
- Compatibility with Symfony4


[Unreleased]: https://github.com/PBXg33k/php-info-base/compare/v1.0.4...HEAD
[1.0.4]: https://github.com/PBXg33k/php-info-base/compare/v1.0.3...v1.0.4
[1.0.3]: https://github.com/PBXg33k/php-info-base/compare/v1.0.2...v1.0.3
[1.0.2]: https://github.com/PBXg33k/php-info-base/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/PBXg33k/php-info-base/compare/v1.0...v1.0.1

[PHPMusicInfo]: https://github.com/PBXg33k/php-music-info