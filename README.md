PHP Properties
==========
[![Latest Stable Version](https://poser.pugx.org/serafim/properties/v/stable)](https://packagist.org/packages/serafim/properties)
[![Build Status](https://travis-ci.org/SerafimArts/Properties.svg)](https://travis-ci.org/SerafimArts/Properties)
[![Code Quality](https://scrutinizer-ci.com/g/SerafimArts/Properties/badges/quality-score.png?b=master)]
(https://scrutinizer-ci.com/g/SerafimArts/Properties/)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE)
[![Total Downloads](https://poser.pugx.org/serafim/properties/downloads)](https://packagist.org/packages/serafim/properties)

PHP Properties implementations based on getters or setters method and used doc-block information.

## Installation

`composer require serafim/properties`

[Package on packagist.org](https://packagist.org/packages/serafim/properties)

## Usage

What about your getters in class? Maybe like this?

```php
class Some
{
    private $property = 23;
    
    public function getProperty()
    {
        return $this->property;
    }
}
```

Or this?

```php
/**
 * @property-read int $property
 */
class Some
{
    private $property = 23;
    
    public function __get($name)
    {
        if ($name === 'property') {
            return $this->property;
        }
    }
}
```

It looks ugly! ...especially when such a code is a lot.

How about to cut like that? Enjoy!

```php
use Serafim\Properties\Properties;

/**
 * @property-read string $some
 */
class Some
{
    use Properties;
    
    protected $some = 'olololo';
}

$object = new Some();
$object->some; // => olololo

// Yay! Its works!

$object->some = 'new value'; // => PHP Error

// And it is real readonly properties!
```

Try to use methods...

```php
use Serafim\Properties\Properties;

/**
 * @property-read int $some
 * @property-read bool $any
 */
class Some
{
    use Properties;
    
    public function getSome()
    {
        return 42;
    }
    
    public function isAny()
    {
        return true;
    }
}

$object = new Some();
$object->some; // => 42
$object->any; // => true

// :3
```

What about writing? Its easy too!

```php
use Serafim\Properties\Properties;

/**
 * @property-write mixed $value
 * @property-write mixed $any
 */
class WritableSome
{
    use Properties;
    
    protected $some;
    
    public function setAny($value)
    {
        //
    }
}

$object = new WritableSome();

$object->some = 42; // It works
$object->any = 100500; // It works too

// What about reading?

$object->some; // => PHP Error 
```

Thats all, Enjoy %)

## How it works?

Trait `Properties` declare `__get` and `__set` methods. 

If you try access to `some` property - it:
- Search for `getSome` method
- Search docblock - `@property-read` or `@property` and check type
- If type is `bool` or `boolean` - search `isSome` method declaration
- Or return `$some` field (if docblock info declares this property). It must be `protected` or `private`

If you write new value inside `some` property - it:
- Search for `setSome` method
- Search docblock - `@property-write` or `@property`
- Read `@property` typehint declaration based on `PSR-5` type declarations: https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md
> `void` type hint does not support
> Generics (`@property \ArrayCollection<\ArrayCollection> $some`) type hint does not support 

- Sets a new value in `$some` property (if docblock info declares this property). It must be `protected` or `private`