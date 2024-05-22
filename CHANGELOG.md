# Changelog

## v6.1.0 (2024-05-22)

### What's new
- Support for dark mode in the missing alt text widget. e4bb2137 by @robdekort

## v6.0.0 (2024-04-19)

### What's new
- Support Statamic v5 and prefix SVG tag attributes. #21 by @robdekort

## v5.4.0 (2024-03-14)

### What's new
- Add response object as a parameter to the form handler's success hook. #20 @prikkprikkprikk

## v5.3.2 (2024-03-13)

### What's fixed
- Add missing utility classes for the missing alt text widget. 83183e07 by @robdekort

## v5.3.1 (2024-03-12)

### What's fixed
- Button event click logic: remove Alpine, add quotes. be6704c7 by @robdekort

## v5.3.0 (2024-03-12)

### What's new
- Add an option to track events from buttons when using Fathom, GTM or GTAG. An update script will take care of the changes needed to the button fieldset. c65fa6e2 by @robdekort

## v5.2.0 (2024-03-12)

### What's new
- Log non-validation form errors to console. #19 by @prikkprikkprikk

## v5.1.3 (2024-03-11)

### What's fixed
- The form hanlder successHook() gets triggered again. f4ce337a by @robdekort

## v5.1.2 (2024-03-09)

### What's improved
- Loop image formats in picture partial. 4d0787ce by @robdekort

## v5.1.1 (2024-03-08)

### What's changed
- Revert #18 and check the widget config array to autamtically see if the widget is present. a175ea3f by @robdekort

## v5.1.0 (2024-03-08)

### What's new
- A config option to disable the missing alt widget and listeners. #18 by @robdekort

## v5.0.1 (2024-03-01)

### What's improved
- Use primary outline colour on Skip to content. 41e57f43 by @robdekort

## v5.0.0 (2024-03-01)

### What's new
- Remove focus rings and use outlines. Follow along with [this PR](https://github.com/studio1902/statamic-peak/pull/384) if you want to update your site. #16 by @robdekort

## v4.4.2 (2024-02-09)

### What's fixed
- Automatically add `x-model="form.{{ honeypot }}"` to the honeypot field in the form partial. 79560c5d by @robdekort

## v4.4.1 (2024-02-09)

### What's fixed
- Spelling in `successHook()`. 1b600222 by @robdekort

## v4.4.0 (2024-01-24)

### What's new
- Don't list missing alt images that are exempt. This update will add a toggle to your images blueprint. 9a6dd4f6 by @robdekort

## v4.3.0 (2024-01-22)

### What's new
- Add flip and orient parameters to picture partial. 7a87b666 by @robdekort

## v4.2.0 (2023-12-22)

### What's new
- When you use tab and focus a toolbar item the toolbar wil now unhide. 568224b8 by @robdekort

## v4.1.0 (2023-12-12)

### What's new
- Add button attributes partial to this addon. An update script will automatically fix references . 6a2e513d by @robdekort
- Button tags should not use default link behaviour. 6a2e513d by @mikemartin.

## v4.0.5 (2023-12-10)

### What's improved
- Handle CSRF tokens more elegant. fe52f063 by @ryanmitchell

## v4.0.5 (2023-12-10)

### What's improved
- Handle CSRF tokens more elegant. fe52f063 by @ryanmitchell

## v4.0.4 (2023-12-09)

### What's fixed
- Expired token errors when using forms with static caching. 6f4255ce by @robdekort

## v4.0.3 (2023-12-08)

### What's improved
- Only show form error summary after submission. d85a58a1 by @robdekort

## v4.0.2 (2023-12-07)

### What's improved
- Add an update script to auto [fix form fields](https://github.com/studio1902/statamic-peak/releases/tag/v16.1.2). 6a054e7b by @robdekort

## v4.0.1 (2023-12-04)

### What's fixed
- Fix statamic conditionals not working #14 by @AndreasSchantl

### What's new
- An update script to automatically update the JS driver in the form partial. a5e7f9bc and f23504fb by @robdekort

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
