[![Build Status](https://secure.travis-ci.org/omerucel/fabrika.png)](http://travis-ci.org/omerucel/fabrika)
[![Coverage](https://coveralls.io/repos/omerucel/fabrika/badge.png?branch=master)](https://coveralls.io/repos/omerucel/fabrika)

# About

Fabrika is a database fixtures replacement, like [django factory_boy](https://github.com/rbarrois/factory_boy), for PHP.
Unfortunatly, now only supported PDO and limited model class. Documentation will update as soon as possible. Sorry
about that..

# Why?

I don't like using PHPUnit/DBUnit datasets. This is the main reason..

# Requirements

- PHP 5.3+

# Installation

Fabrika has composer support. Change your composer.json file and update composer.

```json
{
    "require-dev": {
        "omerucel/fabrika": "dev-master"
    },
}
```

```bash
$ composer update
```

# Usage

This is our model class:

```php
<?php

namespace OurApplication\Model;

class User
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $status;

    /**
     * @var array
     */
    public $service_ids = array();

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status == 1;
    }
}

```

This is our model producer class:
```php
<?php

use Fabrika\ModelProducerProxyAbstract;
use Fabrika\Generator\IntegerSequence;
use Fabrika\Generator\StringSequence;

class UserProducer extends ModelProducerProxyAbstract
{
    protected static $tableName = 'user';
    protected static $modelClass = 'OurApplication\Model\User';
    protected static $excludedFields = array('service_ids');

    public function getDefinition()
    {
        return array(
            'id' => new IntegerSequence(),
            'name' => new StringSequence('name{n}');
        );
    }
}
```

Finally, our test class. You must create manually your database tables! Maybe very usable an abstract test case class
for your tests..

```php
<?php


class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PDO
     */
    protected static $pdo;

    public static function setUpBeforeClass()
    {
        if (self::$pdo == null) {
            self::$pdo = new \PDO('sqlite::memory:');
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        ModelProducerProxyAbstract::init(self::$pdo);
    }

    public function testExample()
    {
        self::$pdo->exec('CREATE TABLE IF NOT EXISTS user(id INTEGER, name)');

        $user1 = UserProducer::create();
        $user2 = UserProducer::create();

        // Your test case lines

        UserProducer::flush();
        // or
        UserProducer::delete($user1->id);
    }
}
```

# Producer Proxies

All producer classes has proxy class. Your model classes must extend a proxy class.

## ArrayProducerProxy

Contains very basic structure for all proxy classes.

## ModelProducerProxy

Extended ArrayProducerProxy. Added create, flush and delete methods.

# Generators

## IntegerSequence

This class generates sequence numeric data. Also you can pass step value. Default step value : 1.

```php
$generator = new IntegerSequence();
assert($generator->generate() == 1);
assert($generator->generate() == 2);

$generator = new IntegerSequence(2);
assert($generator->generate() == 2);
assert($generator->generate() == 4);
```

## StringSequence

This class generates string with sequence numeric data. Also you can pass step value. Default step value : 1.

```php
$generator = new StringSequence('name{n}');
assert($generator->generate() == 'name1');
assert($generator->generate() == 'name2');

$generator = new StringSequence('name{n}', 2);
assert($generator->generate() == 'name2');
assert($generator->generate() == 'name4');
```

## RandomItem

This class selects ramdom data from array.

```php
$generator = new RandomItem(array('a', 'b', 'c'));
assert(in_array(array('a', 'b', 'c'), $generator->generate());
assert(in_array(array('a', 'b', 'c'), $generator->generate());
```

## RandomNumber

This class generates random number between min and max value.

```php
$min = 0;
$max = 3;
$generator = new RandomNumber($min, $max);
assert(in_array(array(0, 1, 2, 3), $generator->generate());
assert(in_array(array(0, 1, 2, 3), $generator->generate());
assert(in_array(array(0, 1, 2, 3), $generator->generate());
assert(in_array(array(0, 1, 2, 3), $generator->generate());
```

# License

The MIT License (MIT)

Copyright © Ömer ÜCEL 2013

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit
persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
