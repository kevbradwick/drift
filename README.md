Drift
=====

[![Build Status](https://travis-ci.org/kevbradwick/drift.svg?branch=master)](https://travis-ci.org/kevbradwick/drift)

---

Drift is a PHP library for mapping arbitrary data to PHP classes.

### Installation

Drift is available on [Packagist](https://packagist.org/packages/kevbradwick/drift), so you can use composer;

    composer require kevbradwick/drift

### Usage

Say you have a class with private member variables like this;

```php
namespace Application;

class Actor
{
    /**
     * @Drift\String()
     */
    private $name;
    
    /**
     * @Drift\Int()
     */
    private $age;
    
    /**
     * @Drift\Date(field="date_of_birth")
     */
    private $dateOfBirth;
}
```
    
And you have some data, possibly consumed from an API, that looks like this;

```php
$data = [
    'name' => 'Arnold Schwarzenegger',
    'age' => 68,
    'date_of_birth' => 'July 30, 1947'
]
```
    
You can then use `Drift\Mapper` to create a new instance of the class,
initialised with the data.

```php
use Drift\Mapper;
use Drift\Reader\AnnotationReader;
use Application\Actor;

$mapper = new Mapper(new AnnotationReader());
$mapper->setData($data);

$actor = $mapper->instantiate(Actor::class);
```

In addition to annotations, you can specify mapping using Yaml or plain old php.

Full documentation can be found on the [Wiki](https://github.com/kevbradwick/drift/wiki).
