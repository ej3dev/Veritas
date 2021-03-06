ej3dev\Veritas
==============

[![Latest Stable Version](https://poser.pugx.org/ej3dev/veritas/v/stable.png)](https://packagist.org/packages/ej3dev/veritas) [![Total Downloads](https://poser.pugx.org/ej3dev/veritas/downloads.png)](https://packagist.org/packages/ej3dev/veritas) [![Latest Unstable Version](https://poser.pugx.org/ej3dev/veritas/v/unstable.png)](https://packagist.org/packages/ej3dev/veritas) [![License](https://poser.pugx.org/ej3dev/veritas/license.png)](https://packagist.org/packages/ej3dev/veritas)
 
A pragmatic and concise validation library written in PHP.

The idea behind [**Veritas**](http://en.wikipedia.org/wiki/Veritas) is implement a flexible easy to use validation engine so you can write validation code as easy as you can build questions in everyday language.

```php
// Is $var a decimal value in the interval [-1,1]?
v::is($var)->dec()->in('[-1,1]')->verify();

// Is $var an array with a index 8 with value 'eight'?
v::is($var)->arr()->key(8,'eight')->verify();

// Is $var a string that represent a date in format year-month-day?
v::is($var)->str()->date('Y-m-d')->verify();

// Is $var an object with a property called 'name'?
v::is($var)->obj()->attr('name')->verify();

//Is $mail a valid email address from Gmail or Yahoo?
v:isEmail($mail)->contains('@gmail','@yahoo')->verify(); 
```



Requirements
------------

**Veritas** requires PHP 5.3 or later.



Installation
------------

**Veritas** is a *one-file-project* with *zero dependencies* available on [GitHub](https://github.com/ej3dev/Veritas) and [Packagist](https://packagist.org/packages/ej3dev/veritas). You have two options to install it: 

### Composer

Use [Composer](https://getcomposer.org) dependency Manager for PHP. Add the following to your `composer.json` and run `composer update`.

```json
"require": {
    "ej3dev/veritas": "~0.6"
}
```

### Single file require

Download the project package, find the file `Verifier.php` and copy it wherever you want in your project working directory. Then use `require_one` to include the file in your code:

```php
require_once('path/to/Verifier.php');
```



Use
---

### Composer autoload or single file require

**Veritas** support *PSR-4 autoloading* via [Composer](https://getcomposer.org). Import third party code to your context with [Composer autoloader](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
<?php
//Composer autoloader
require('vendor/autoload.php');

//Your code
//...
```

or use classic file require:

```php
<?php
//Single file require
require_once('path/to/Verifier.php');

//Your code
//...
```

### Namespace import

**Veritas** is namespaced, but you can write less code using a single class into your context:

```php
use ej3dev\Veritas\Verifier as v;
```


### How validate a variable

You can validate any `$var` in three simple steps: 

- **1. Create new `Verifier` instance**
```php
v::is($var);  //Created new instance loaded with $var data
```


- **2. Add rules**
```php
v::is($var)->int()->in('[0,9]'); //Add rules to verify an integer between 0 and 9
```

- **3. Verify and get the result**
```php
$result = v::is($var)->int()->in('[0,9]')->verify(); //Run tests and get true or false
//If $var=8 then $result=true
//If $var=10 then $result=false  
``` 


### Simple example

The *Hello World* example is something like this:

```php
<?php
//Composer autoload
require('vendor/autoload.php');
use ej3dev\Veritas\Verifier as v;

$hello = 'Hello World!';
//Is $hello a string?
v::is($hello)->str()->verify(); //true
```


### Chained validation

The available validators can be chained to build complex rules:

```php
$number = 8;
//Is $number an integer included in the list 2,4,6,8?
v::is($number)->int()->in(2,4,6,8)->verify(); //true
//Is $number a integer included in the list 1,3,5,7,9?
v::is($number)->int()->out(1,3,5,7,9)->verify(); //false

$decimal = 3.14;
//Is $decimal a decimal number in the close interval [1,5]?
v::is($decimal)->dec()->in('[1,5]')->verify(); //true
//Is $decimal a decimal number in the open interval (-1,1)?
v::is($decimal)->dec()->in('(-1,1)')->verify(); //false

$value = 'two';
//Is $value a string included in the list one, two, three?
v::is($value)->str()->in('one','two','three')->verify(); //true
```



### Output

All validators and rules returns the `Verifier` instance to support [fluent interface](http://en.wikipedia.org/wiki/Fluent_interface) via [method chaining](http://en.wikipedia.org/wiki/Method_chaining). `verify()` method is used to get the validation final result after apply all chained rules and validators. By default, `verify()` return `true` or `false` but you can change this behavior calling the method with one or two parameters. For example:

```php
$pi = 3.1416;
$euler = 2.7183

//Default
v::is($pi)->ineq('>',$euler)->verify(); //Return: true
v::is($pi)->eq($euler)->verify(); //Return: false

//One parameter
v::is("8")->str()->num()->verify(
	'String with numeric value'
); //Return: 'String with numeric value'
v::is("eight")->str()->num()->verify(
	'String with numeric value'
); //Return: false

//Two parameters
v::is($pi)->in('[3.14,3.142)')->verify(
	'Number too close to pi',
	'This number is not pi'
); //Return: Number too close to pi
v::is($euler)->in('[3.14,3.142)')->verify(
	'Number too close to pi',
	'This number is not pi'
); //Return: This number is not pi
```

Parameters of `verify()` can be [anonymous functions](http://www.php.net/manual/en/functions.anonymous.php):

```php
$email = 'mail@domain.com';
v::isEmail($email)->verify(
	function($email) { mail($email,'Hello','Lorem ipsum dolor sit amet...'); },
	function() { exit('Error: Invalid email address'); }
);

```



Build-in validators
-------------------

Documentation is work-in-progress. Meanwhile you can take a look to the examples in the file `tests/VerifierTest.php`

### is()

### isEmail()

### isUrl()

### isIp()

### isNull()

### isNotNull()

### isEmpty()

### isNotEmpty()



Type validators
----------------

Documentation is work-in-progress. Meanwhile you can take a look to the examples in the file `tests/VerifierTest.php`

### boo()

### int()

### dec()

### num()

### str()

### arr()

### obj()

### res()



Rules
-----

Documentation is work-in-progress. Meanwhile you can take a look to the examples in the file `tests/VerifierTest.php`

### attr()

### contain()

### containAny()

### date()

### eq()

### filter()

### in()

### ineq()

### key()

### len()

### out()

### regex()

### value()

### without()



Prefix `not` for validators and rules
-------------------------------------

You can prepend any rule or type validator with prefix "not" to get the logical negation of that rule or type validator:

```php
$number = 8;
v::is($number)->int()->verify(); //true
v::is($number)->notInt()->verify(); //false
v::is($number)->dec()->verify(); //false
v::is($number)->notDec()->verify(); //true

$pi = 3.14;
v::is($pi)->in('[-1,1]')->verify(); //false
v::is($pi)->notIn('[-1,1]')->verify(); //true
v::is($pi)->ineq('<=',3.0)->verify(); //false
v::is($pi)->notIneq('<=',3.0)->verify(); //true
```



Changelog
---------

### v0.6.0 [master][Fr.23-May-2014]
- New validator type: `dec()`
- Added support for prefix `not` in all rules and type validators
- Added support for Badge Poser tags in README.md
- Verifier class tested against 484 unitary tests
- Small code fixed

### v0.5.1 [master][Tu.20-May-2014]
- Verifier.php code fully documented
- `Verifier` constructor now support a optional parameter to configure init value for `$test` variable
- New built-in validators: `isEmpty()`, `isNotEmpty()`
- New rule: `containAny()`
- Small code optimizations 

### v0.5.0 [master][Th.8-May-2014]
- First public release
- Added [Composer](https://getcomposer.org) support
- Project published on [GitHub](https://github.com/ej3dev/Veritas) and [Packagist](https://packagist.org/packages/ej3dev/veritas)



License
-------

The MIT License (MIT)

Copyright (c) 2014 Emilio José Jiménez <ej3dev@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.