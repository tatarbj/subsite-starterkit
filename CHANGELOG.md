# Subsite Starterkit change log

## Version 2.2.3

### New features:
  * MULTISITE-16043 - Allow developers to override phing targets through resources/build.custom.xml
  * MULTISITE-15953 - Added option to keep site during build-dev command or just use rebuild-dev

### Improvements:
  * MULTISITE-15551 - Added global platform download caching mechanism and store them per version

### Bug fixes
  * MULTISITE-16650 - Missing host parameter caused build-clone and install-dev to fail
  * MULTISITE-16522 - Removed obsolete theme_default parameter for build-clone command
  * MULTISITE-16209 - Added forgotten file_temporary_path parameter to build-clone command


## Version 2.2.4

### New features:
  * MULTISITE-17107 - Allow developers to use custom scripts on composer and git hooks


## Version 2.2.5

### Improvements:
  * MULTISITE-17096 - Change symlink paths from absolute to relative for CI purposes

### Bug fixes
  * MULTISITE-17097 - Hotfix for the pre-push phpcs script activation and de-activation
