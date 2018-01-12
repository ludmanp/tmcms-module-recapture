# tmcms-module-recapture
reCapture for The Modern Cms

## Installation

``` bash
$ composer require ludmanp/tmcms-module-recapture
```

## Basic usage

In CMS menu reCaptcha/Settings fill `key' and `secret` for reCaptcha from https://www.google.com/recaptcha/admin.
Settings items will appear after first test usage of binding button and validation. Or you can create then in CMS Settings (`http://your.site/cms/?p=settings&do=_default`) module manually, assigning `module` value `recaptcha`.

### Bind to button

```php
use \TMCms\Modules\Recaptcha\ModuleRecaptcha;

echo ModuleRecaptcha::renderButton(w('Send'), ['class'=>'btn btn-primary'], 'contactForm');
```

Will render form submit button

```html
<button class="btn btn-primary g-recaptcha" type="submit" data-sitekey="Your-ReCaptchure-Key " data-callback="onSubmit">Send</button>
```

Parameters:

* **text**:  Button caption
* **options**:  Array of button tag attributes. Can include any attributes including `type`, `data-sitekey` and `data-callback`.
* **prepareFormID**:  Form ID. If this parameter not set, then you will need call `ModuleRecaptcha::renderButton` or manually include scripts for initializing reCaptcha.

**Returns** string with html for button.

### Validate

On server side after post request call

```php
ModuleRecaptcha::validate()
```

**Returns** boolean success value.

For receiving array of error codes call `ModuleReaptcha::getErrors()`.
