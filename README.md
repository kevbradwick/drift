Drift
=====

[![Build Status](https://travis-ci.org/kevbradwick/drift.svg?branch=master)](https://travis-ci.org/kevbradwick/drift)

---

Drift is a PHP library for mapping arbitrary data to PHP classes.

### Installation

This library is currently in development so you can install master with;

    composer require kevbradwick/drift:dev-master

### Usage

Say you have a class with private member variables like this;

    namespace Application;
    
    class Actor
    {
        private $name;
        
        private $age;
        
        private $dateOfBirth;
    }
    
And you have some data, consumed from an API like this;

    $data = [
        'name' => 'Arnold Schwarzenegger',
        'age' => 68,
        'date_of_birth' => 'July 30, 1947'
    ]
    
And you want to create a new instance of your actor with the data mapped
correctly to each member variable.

Then you create a configuration file;

    Application\Actor:
        name:
            type: string
        age:
            type: int
        dateOfBirth:
            type: date
            field: date_of_birth
            
And use the `Drive\Mapper` to instantiate a new class;

    use Drift\Mapper;
    use Drift\Reader\YamlReader;
    use Application\Actor;
    
    $mapper = new Mapper(new YamlReader('path/to/config.yml'));
    $mapper->setData($data);
    
    $actor = $mapper->instantiate(Actor::class);
