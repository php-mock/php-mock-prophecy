# Mock PHP built-in functions with Prophecy

This package integrates the function mock library
[PHP-Mock](https://github.com/php-mock/php-mock) with Prophecy.

# Installation

Use [Composer](https://getcomposer.org/):

```sh
composer require php-mock/php-mock-prophecy
```

# Usage

Build a new [`PHPProphet`](http://php-mock.github.io/php-mock-prophecy/api/class-phpmock.prophecy.PHPProphet.html)
and create function prophecies for a given namespace
with [`PHPProphet::prophesize()`](http://php-mock.github.io/php-mock-prophecy/api/class-phpmock.prophecy.PHPProphet.html#_prophesize):

```php
<?php

namespace foo;

use phpmock\prophecy\PHPProphet;

$prophet = new PHPProphet();

$prophecy = $prophet->prophesize(__NAMESPACE__);
$prophecy->time()->willReturn(123);
$prophecy->reveal();

assert(123 == time());
$prophet->checkPredictions();
```

## Restrictions

This library comes with the same restrictions as the underlying
[`php-mock`](https://github.com/php-mock/php-mock#requirements-and-restrictions):

* Only *unqualified* function calls in a namespace context can be prophesized.
  E.g. a call for `time()` in the namespace `foo` is prophesizable,
  a call for `\time()` is not.

* The mock has to be defined before the first call to the unqualified function
  in the tested class. This is documented in [Bug #68541](https://bugs.php.net/bug.php?id=68541).
  In most cases you can ignore this restriction. But if you happen to run into
  this issue you can call [`PHPProphet::define()`](http://php-mock.github.io/php-mock-prophecy/api/class-phpmock.prophecy.PHPProphet.html#_define)
  before that first call. This would define a side effectless namespaced function.

* Additionally it shares restrictions from Prophecy as well:
  Prophecy [doesn't support pass-by-reference](https://github.com/phpspec/prophecy/issues/225).
  If you need pass-by-reference in prophecies, consider using another framework
  (e.g. [php-mock-phpunit](https://github.com/php-mock/php-mock-phpunit)).

# License and authors

This project is free and under the WTFPL.
Responsable for this project is Markus Malkusch markus@malkusch.de.

## Donations

If you like this project and feel generous donate a few Bitcoins here:
[1335STSwu9hST4vcMRppEPgENMHD2r1REK](bitcoin:1335STSwu9hST4vcMRppEPgENMHD2r1REK)

[![Build Status](https://travis-ci.org/php-mock/php-mock-prophecy.svg?branch=master)](https://travis-ci.org/php-mock/php-mock-prophecy)
