[![Build Status](https://secure.travis-ci.org/omerucel/fabrika.png)](http://travis-ci.org/omerucel/fabrika)
[![Coverage Status](https://coveralls.io/repos/omerucel/fabrika/badge.png?branch=master)](https://coveralls.io/r/omerucel/fabrika?branch=master)

# About

Fabrika is a database fixtures replacement for PHP.

# Requirements

- PHP 5.3+

# Usage

```php
<?php

use Fabrika\Generator\AutoIncrement;
use Fabrika\Generator\StringSequence;

$pdo = new \PDO('mysql:host=127.0.0.1;port=3306;dbname=test;charset=utf8', 'root', '');
$connection = new Fabrika\Producer\Database\Connection\MySQL($pdo);
$producer = new Fabrika\Producer\DatabaseProducer($connection);

$producer->define('user')
    ->addField('id', new AutoIncrement())
    ->addField('username', new StringSequence('username{n}'));

$user1 = $producer->create('user');
$user2 = $producer->create('user');

assert($user1->id == 1);
assert($user1->username == 'username1');

assert($user2->id == 2);
assert($user2->username == 'username2');
```

# Installation

conposer.json:
```json
{
    "require-dev": {
        "omerucel/fabrika": "dev-master"
    },
}
```

```bash
$ composer install
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
