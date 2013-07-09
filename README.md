[![Build Status](https://secure.travis-ci.org/omerucel/fabrika.png)](http://travis-ci.org/omerucel/fabrika)
[![Coverage](https://coveralls.io/repos/omerucel/fabrika/badge.png?branch=master)](https://coveralls.io/repos/omerucel/fabrika)

# Hakkında

Fabrika is a fixtures replacement, like [django factory_boy](https://github.com/rbarrois/factory_boy).

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

use Fabrika\Producer\ModelProducer;
use Fabrika\Generator\IntegerSequence;
use Fabrika\Generator\StringSequence;

class UserProducer extends ModelProducer
{
    protected static $tableName = 'user';
    protected static $modelClass = 'OurApplication\Model\User';

    public function getDefinition()
    {
        return array(
            'id' => new IntegerSequence(),
            'name' => new StringSequence('name{n}');
        );
    }
}
```

Finally, our test class:
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
        $user1 = UserProducer::create();
        $user2 = UserProducer::create();

        // Your test case lines

        UserProducer::flush();
        // or
        UserProducer::delete($user1->id);
    }
}
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