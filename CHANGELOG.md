# Changelog

## v4.0.0 (2023-12-01)

### What's new
- Breaking change: Laravel Precognition support. Removes the old style form handler. Don't upgrade to this version if you don't manually change your templates according to [this PR](https://github.com/studio1902/statamic-peak/pull/359). #13 by @robdekort

## v3.4.3 (2023-11-21)

### What's improved
- Fix tpyo that caused picture errors. 89cd1114 by @robdekort

## v3.4.2 (2023-11-21)

### What's improved
- Add a more solid check to the picture partial to prevent divisons by zero. 08675838 by @robdekort

## v3.4.1 (2023-11-17)

### What's improved
- Roll-back bb620244. d40dccd4 by @robdekort

## v3.4.0 (2023-11-16)

### What's improved
- Add extra image width check to picture partial to prevent divisons by zero. bb620244 by @robdekort

## v3.3.0 (2023-11-14)

### What's improved
- Implement a better caching solution for the no alt text widget. #12 by @robdekort

## v3.2 (2023-05-11)

### What's improved
- Simplify live preview morph partial. #49e3c22a by @jacksleight

## v3.1 (2023-05-10)

### What's improved
- Make live preview work with static caching. Can and will be simplified later when [this PR](https://github.com/statamic/cms/pull/8100) gets merged. #e09f4cf0 by @jacksleight

## v3.0 (2023-05-09)

**Breaking changes**: If you upgrade an existing site make sure to apply the [changes made to Peak Core in v12](https://github.com/studio1902/statamic-peak/releases/tag/v12.0).

### What's new
- Statamic v4 support: include the MissingAltWidget in this addon and restyle it to fit the updated CP. #8 by @robdekort
- Add support for morphing live preview edits instead of refreshing the browser. #8 by @jacksleight and @robdekort

## v2.5 (2023-04-03)

### What's improved
- Allow token refreshing from any host while in local environment. #7 by @marcorieser

## v2.4 (2023-03-31)

### What's fixed
- Fix context for `this.success` in form handler. #6 by @marcorieser

## v2.3 (2023-03-24)

### What's improved
- Add the option to use a different srcset when using the picture partial. #4 by @marcorieser

## v2.2 (2023-03-18)

### What's improved
- Use partial tag method. 6a0bea92 by @robdekort

## v2.1 (2023-03-09)

### What's improved
- Include the Statamic Conditional field form helpers. a53558a3 by @robdekort

## v2.0 (2023-03-09)

### What's new
- Add the Peak Starter Kit form handler logic to this addon. #3 by @robdekort

## v1.1 (2023-02-09)

### What's new
- Add ability to publish the views. #2 by @marcorieser

## v1.0 (2023-02-09)

- Initial release.
