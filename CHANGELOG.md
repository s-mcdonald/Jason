# Changelog

## 0.0.3 - Added ability to load Json from file
### Release Highlights
 * Added
   * New static method `Json::toJsonString($object):string` which will mitigate the deprecation of `Serialize`.
   * Added ability to load Json from file via `Json::fromFile('path/to/file.json')`
   * Added `JSON_UNESCAPED_SLASHES` constant to the pretty output.
* Deprecations
  * Marked `Jason::Serialize()` as deprecated for removal on next release.
## 0.0.2 - Added new Jason static class 
### Release Highlights
 * Added
   * [Added new Jason static class](https://github.com/s-mcdonald/Jason/commit/9b184b1d066357631eda17d2a12dee3bfcb331d1)
   * Added ability to serialize methods.
   * Serializer now has `$allowStatics` in constructor allowing user to choose if they want to serialize static values.
 * Bugfix
   * if a value is not initialized then the serializer will not add property to the Json value. 
## 0.0.1 - Initial release