# Dynamic Options Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## 4.0.0 - 2026-10-20
### Changed
- Craft CMS 5 port (requires Craft 5 and PHP 8.2). 3.0.0 only raised the Craft requirement; this release brings the field types themselves up to Craft 5’s field API.
- The Dynamic Multi Options field uses Craft’s own multi-select (selectize) input instead of its own jQuery set-up script.
- A Dynamic Multi Options field’s value is always an array of strings (empty when nothing is chosen). Content saved under Craft 4 as a JSON string is read as an array too, so templates no longer need to `json_decode` it.
- A Dynamic Options field’s value is a string, or `null` when empty.
- Both field types have their own icon in the field layout designer.
- The settings field explains the format the Twig must output.

### Fixed
- The fields now work in Craft 5’s inline (element index) editing, and fire Craft’s `defineInputHtml` event.
- If a field’s Twig throws or does not output valid JSON, the field shows an error in place of its input (and logs why) instead of breaking the whole edit page.
- A saved value that is no longer among the options is kept and shown, so saving an element no longer silently changes a dropdown to its first option or drops values from a multi-select.
- Clearing every value of a Dynamic Multi Options field saves it as empty.
- Element index columns show a Dynamic Multi Options field’s values as a comma-separated list.

### Removed
- The unused field asset bundle and input template.

## 1.0.0 - 2018-05-25
### Added
- Initial release
