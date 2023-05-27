# Changelog

## 0.1.0 - Version 1 - 27-May-2023
### Release Highlights
* !! Semver versioning begins !!
* [Semver versioning](https://semver.org/) standard start at v1.0.0
  *  From this point on this package will follow;
     * MAJOR version when incompatible API changes.
     * MINOR version when new functionality in a backward compatible manner.
     * PATCH version when any bugfixes are made in backward compatible manner.

* Added
  * `createFromFile` on the Json static to create a new Instance of `Json`
  * `createFromStringable` on the Json static to create a new Instance of `Json`
  * `createFromUrl` on the Json static to create a new Instance of `Json`
  * `createFromArray` on the Json static to create a new Instance of `Json`
  * Json entity methods added [`toString()`, `toPretty()`, `toObject()`]
  * `InvalidPropertyException`

* BC Break
  * Removed Jason static in favour of Json static 
  * `Json::toArray` renamed to `Json::convertJsonToArray` (reserving to and from prefix for the instantiated object)
  * `Json::toObject` renamed to `Json::convertFromJsonToObject` (reserving to and from prefix for the instantiated object)

Many other minor fixes and refactoring. This is the first main release, so at this point on all changes will be documented
and deprecations will be given at least 2 versions before they are removed.

## 0.0.9 - Removed: Jason static
### Release Highlights
* BC break
  * Removed the Jason static, and now you can access the same functions via Json static 

## 0.0.8 - Added: Decoder and ByPassDecoder
### Release Highlights
* Added
  * new Decoder and ByPass Decoder
  * fixed bitwise operator to setting flags

## 0.0.7 - Bugfixes: Bitwise operator fix and bigIntAsString fix
### Release Highlights
* Bugfix
  * fixed $bigIntAsString on serializer now fixed will now display string for large ints
  * fixed bitwise operator to setting flags
 
## 0.0.6 - Deprecated Jason static in favour of new Json static
### Release Highlights
* Added 
  * Added new `Json` static, This class currently extends the `Jason` static class can do.
  * Added new `JsonSerializer` and `JasonSerializable`.
* Deprecated Jason static in favour of new Json static
  `Jason` will be removed in a future release.
* Bugfix
  * Removed readonly identifier from arguments as package support php 8 

## 0.0.5 - Added `toArray` and `fromUrl` on static Jason
### Release Highlights
* Added
  * Added Jason::fromUrl(urlString)
  * Added Jason::toArray(str)
  * Added additional Exception classes; `JsonDecodeException` and `JsonEncodeException`.
  * Added some tests, just a start will need more
* Bugfix
  * Properties and methods were being serialized when are to be excluded. This is now fixed.

## 0.0.4 - Added `JsonAsserter`
### Release Highlights
* Added
   * New `JsonAsserter` for validating string values as valid Json.
   * Added `InvalidArgumentException` exception.
   * Added tests
 * BC Break
   * `Jason::Serialize(JasonSerializable)` now lowercase 'S'

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