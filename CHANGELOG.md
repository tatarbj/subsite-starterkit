# Subsite Starterkit change log

## Version 2.2.3 + [qa-automation 2.4](https://github.com/ec-europa/qa-automation/blob/master/CHANGELOG.md#version-24

### New features:
  * MULTISITE-16043 - Allow developers to override phing targets through resources/build.custom.xml
  * MULTISITE-15953 - Added option to keep site during build-dev command or just use rebuild-dev

### Improvements:
  * MULTISITE-15551 - Added global platform download caching mechanism and store them per version

### Bug fixes
  * MULTISITE-16650 - Missing host parameter caused build-clone and install-dev to fail
  * MULTISITE-16522 - Removed obsolete theme_default parameter for build-clone command
  * MULTISITE-16209 - Added forgotten file_temporary_path parameter to build-clone command
