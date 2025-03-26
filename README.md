hCAPTCHA for Laravel
==========

[![Build Status](https://travis-ci.org/anhskohbo/no-captcha.svg?branch=master&style=flat-square)](https://travis-ci.org/anhskohbo/no-captcha)
[![Latest Stable Version](https://poser.pugx.org/anhskohbo/no-captcha/v/stable)](https://packagist.org/packages/anhskohbo/no-captcha)
[![Total Downloads](https://poser.pugx.org/anhskohbo/no-captcha/downloads)](https://packagist.org/packages/anhskohbo/no-captcha)
[![Latest Unstable Version](https://poser.pugx.org/anhskohbo/no-captcha/v/unstable)](https://packagist.org/packages/anhskohbo/no-captcha)
[![License](https://poser.pugx.org/anhskohbo/no-captcha/license)](https://packagist.org/packages/anhskohbo/no-captcha)

![recaptcha_anchor 2x](https://cloud.githubusercontent.com/assets/1529454/5291635/1c426412-7b88-11e4-8d16-46161a081ece.gif)

> For Laravel 4 use [v1](https://github.com/anhskohbo/no-captcha/tree/v1) branch.

## Installation

```
composer require bataboom/h-captcha:dev-master
```

## Laravel 5 and above

### Setup

**_NOTE_** This package supports the auto-discovery feature of Laravel 5.5 and above, So skip these `Setup` instructions if you're using Laravel 5.5 and above.

In `app/config/app.php` add the following :

1- The ServiceProvider to the providers array :

```php
BataBoom\Captcha\HCaptchaServiceProvider::class,
```

2- The class alias to the aliases array :

```php
'HCaptcha' => BataBoom\Captcha\Facades\HCaptcha::class,
```

3- Publish the config file

```ssh
php artisan vendor:publish --provider="BataBoom\Captcha\HCaptchaServiceProvider"
```

### Configuration

Add `HCaptcha_SECRET` and `HCaptcha_SITEKEY` in **.env** file :

```
HCAPTCHA_SECRET=secret-key
HCAPTCHA_SITEKEY=site-key
```

(You can obtain them from [here](https://www.google.com/recaptcha/admin))

### Usage

#### Init js source

With default options :

```php
 {!! HCaptcha::renderJs() !!}
```

With [language support](https://developers.google.com/recaptcha/docs/language) or [onloadCallback](https://developers.google.com/recaptcha/docs/display#explicit_render) option :

```php
 {!! HCaptcha::renderJs('fr', true, 'recaptchaCallback') !!}
```

#### Display reCAPTCHA

Default widget :

```php
{!! HCaptcha::display() !!}
```

With [custom attributes](https://developers.google.com/recaptcha/docs/display#render_param) (theme, size, callback ...) :

```php
{!! HCaptcha::display(['data-theme' => 'dark']) !!}

{!! HCaptcha::display(['data-theme' => 'dark', 'data-callback' => 'onSubmit', 'data-theme' => 'dark']) !!}
```

Invisible reCAPTCHA using a [submit button](https://developers.google.com/recaptcha/docs/invisible):

```php
{!! HCaptcha::displaySubmit('my-form-id', 'submit now!', ['data-theme' => 'dark']) !!}
```
Notice that the id of the form is required in this method to let the autogenerated 
callback submit the form on a successful captcha verification.

#### Validation

Add `'h-captcha-response' => 'required|captcha'` to rules array :

```php
$validate = Validator::make(Input::all(), [
	'h-captcha-response' => 'required|captcha'
]);

```

##### Custom Validation Message

Add the following values to the `custom` array in the `validation` language file :

```php
'custom' => [
    'h-captcha-response' => [
        'required' => 'Please verify that you are not a robot.',
        'captcha' => 'Captcha error! try again later or contact site admin.',
    ],
],
```

Then check for captcha errors in the `Form` :

```php
@if ($errors->has('h-captcha-response'))
    <span class="help-block">
        <strong>{{ $errors->first('h-captcha-response') }}</strong>
    </span>
@endif
```

### Testing

When using the [Laravel Testing functionality](http://laravel.com/docs/5.5/testing), you will need to mock out the response for the captcha form element.

So for any form tests involving the captcha, you can do this by mocking the facade behavior:

```php
// prevent validation error on captcha
HCaptcha::shouldReceive('verifyResponse')
    ->once()
    ->andReturn(true);

// provide hidden input for your 'required' validation
HCaptcha::shouldReceive('display')
    ->zeroOrMoreTimes()
    ->andReturn('<input type="hidden" name="h-captcha-response" value="1" />');
```

You can then test the remainder of your form as normal.

When using HTTP tests you can add the `h-captcha-response` to the request body for the 'required' validation:

```php
// prevent validation error on captcha
HCaptcha::shouldReceive('verifyResponse')
    ->once()
    ->andReturn(true);

// POST request, with request body including h-captcha-response
$response = $this->json('POST', '/register', [
    'h-captcha-response' => '1',
    'name' => 'John',
    'email' => 'john@example.com',
    'password' => '123456',
    'password_confirmation' => '123456',
]);
```

## Without Laravel

Checkout example below:

```php
<?php

require_once "vendor/autoload.php";

$secret  = 'CAPTCHA-SECRET';
$sitekey = 'CAPTCHA-SITEKEY';
$captcha = new \BataBoom\Captcha\HCaptcha($secret, $sitekey);

if (! empty($_POST)) {
    var_dump($captcha->verifyResponse($_POST['h-captcha-response']));
    exit();
}

?>

<form action="?" method="POST">
    <?php echo $captcha->display(); ?>
    <button type="submit">Submit</button>
</form>

<?php echo $captcha->renderJs(); ?>
```

## Contribute

https://github.com/anhskohbo/no-captcha/pulls
https://github.com/BataBoom/h-captcha/pulls