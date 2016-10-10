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

How your classes with getters looks like? Maybe like this?

```php
/**
 * Class MyClass.
 * @property-read int $a
 * @property-read string $b
 */
class MyClass
{
    /**
     * Property $a must be a protected for intercepts of __get method 
     * @var int
     */
    protected $a = 23;
    
    /**
     * ...Same
     * @var int
     */
    protected $b = 'string';

    /**
     * Getter for read-only $a and $b properties
     * @param string $field
     * @return int|null
     */
    public function __get($field)
    {
        switch (true) {
            case 'a': return $this->a;
            case 'b': return $this->b;
        }
        
        return null;
    }
}
```

It looks ugly! ...especially when such a code is a lot.

How about to cut like that? Enjoy!

```php
use Serafim\Properties\Properties;

/**
 * Class MyClass.
 * @property-read int $a
 * @property-read string $b
 */
class MyClass
{
    use Properties;
    
    /**
     * @var int
     */
    protected $a = 23;
    
    /**
     * @var int
     */
    protected $b = 'string';
}
```

And it declaration will be works for readonly properties: 

```php
$dto = new MyClass;
$dto->a; // => 23
$dto->b; // => 'string'
```

Try to write a new value:

```php
$dto = new MyClass;
$dto->a = 42; // Error: "AccessDeniedException: Can not set value to read-only property MyClass::$a"
```

All declarations (`@property`, `@property-read` and `@property-write`) works in IDE:

![https://psv4.vk.me/c810625/u12526981/docs/e9661ce5d3a5/1111.png?extra=YzddyuUyNI2glXuQWfMK0IjLtbUeXFEmVXDSRp2-0Orb4GhbsQXYT5sjGcMZa1UX9LJTb0ZxHhBxw_Oq3R2sJJNrVU-pDxvEb9dkou02FQ6b5mTUvJ2iKg](https://psv4.vk.me/c810625/u12526981/docs/e9661ce5d3a5/1111.png?extra=YzddyuUyNI2glXuQWfMK0IjLtbUeXFEmVXDSRp2-0Orb4GhbsQXYT5sjGcMZa1UX9LJTb0ZxHhBxw_Oq3R2sJJNrVU-pDxvEb9dkou02FQ6b5mTUvJ2iKg)

## Getters and setters override

For getter or setter override just declare 
    `get[Property]` (`is[Property]` for booleans will be works too) or `set[Property]` methods.

```php
/**
 * @property-read int $a
 */
class MyClass
{
    use Properties;
    
    /**
     * @var int
     */
    protected $a = 23;
    
    /**
     * @return int
     */
    protected function getA() 
    {
        return $this->a + 19;
    }
}


echo (new MyClass)->a; // 42 (because 23 + 19 = 42)
```

Setter:

```php
// ...
class Some 
{
   // ... 
   protected $anotherProperty = 'some';
   
    /**
     * @param string $newVal
     */
   public function setAnotherProperty($newVal) 
   {
       // Just example
       if (mb_strlen($newVal) > 4) {
           throw new InvalidArgumentException('...');
       }
       
       $this->anotherProperty = $newVal;
   }
}
```


## Type checking support

All properties with writeable behavior will be "type checkable".

```php
/**
 * @property int|null $a
 */
class Some
{
    use Properties;
    
    protected $a; 
}

//

$some = new Some;
$some->a = 23; // Ok
$some->a = null; // Ok
$some->a = 'string'; // Error: "TypeError: Value for property Some::$a must be of the type int|null, string given"
```

> Type declarations are case insensitive

### Definitions

Definitions are compatible with [PSR-5 standard](https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md#714-property) 
and follows [ABNF](https://tools.ietf.org/html/rfc5234) definitions:

```
type-expression  = type *("|" type)
type             = class-name / keyword / array
array            = (type / array-expression) "[]" / generic
array-expression = "(" type-expression ")"
generic          = collection-type "<" [type-expression "," *SP] type-expression ">"
collection-type  = class-name / "array"
class-name       = ["\"] label *("\" label)
label            = (ALPHA / %x7F-FF) *(ALPHA / DIGIT / %x7F-FF)
keyword          = "array" / "bool" / "callable" / "false" / "float" / "int" / "mixed" / "null" / "object" /
keyword          = "resource" / "self" / "static" / "string" / "true" / "void" / "$this"
```


Primitive types (`@property [primitive] $a`):

- `int` (or `integer`) - property value are integer
- `bool` (or `boolean`) - value are boolean
- `float` (or `double`) - value are float
- `string` - value are string or object with `__toString` method
- `null` (or `void`) - value are nullable
- `resource` - value are resource
- `object` - value can be any object 
- `mixed` - no type checking
- `callable` (or `closure`) - value can be string, instance of \Closure, array with 2 args or object with `__invoke` method
> _`closure` alias will be **deprecated** in future_

- `scalar` - value cannot be an object
> _Does **not available** yet: it will be supports in future_

- `self` - value can be object of self class or string with name of self class
> _Does **not available** yet: it will be supports in future_

- `static` - value can be instance of self class or string whos are sublass of self 
> _Does **not available** yet: it will be supports in future_

- `$this` - value can be only object instance of self class
> _Does **not available** yet: it will be supports in future_

Arrays and collections

- `array` - value is type of array
- `Class[]` - value is type of array or instance of \Traversable
- `scalar[]` - value is type of array or instance of \Traversable
- `Collection<>` - value is type of array or instance of "Collection" and \Traversable
- `Collection<T>` - value is type of array or instance of "Collection" and \Traversable
- `Collection<T,V>`- value is type of array or instance of "Collection" and \Traversable

See more: https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md#appendix-a-types

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

- Sets a new value in `$some` property (if docblock info declares this property). It must be `protected` or `private`
