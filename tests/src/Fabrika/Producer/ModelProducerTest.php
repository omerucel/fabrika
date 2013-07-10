<?php

namespace Fabrika\Producer;

use Fabrika\Generator\IntegerSequence;
use Fabrika\Generator\StringSequence;

class ModelProducerTest extends \PHPUnit_Framework_TestCase
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
    }

    public function testInstanceOf()
    {
        $producer = new ModelProducer(self::$pdo);
        $this->assertInstanceOf('Fabrika\Producer\ArrayProducer', $producer);
    }

    public function testBuild()
    {
        $tableName = 'user';
        $modelClass = 'Fabrika\Producer\Fake\User';
        $producer = new ModelProducer(self::$pdo, $tableName, $modelClass);

        $producer->setDefinition(
            array(
                'id' => new IntegerSequence(),
                'name' => new StringSequence('name{n}')
            )
        );

        /**
         * @var \Fabrika\Producer\Fake\User $user1
         * @var \Fabrika\Producer\Fake\User $user2
         */
        $user1 = $producer->build();
        $user2 = $producer->build();
        $this->assertInstanceOf($modelClass, $user1);
        $this->assertInstanceOf($modelClass, $user2);
        $this->assertEquals('name1', $user1->name);
        $this->assertEquals('name2', $user2->name);
    }

    public function testCreate()
    {
        $tableName = 'user';
        $modelClass = 'Fabrika\Producer\Fake\User';
        $producer = new ModelProducer(self::$pdo, $tableName, $modelClass);

        $producer->setDefinition(
            array(
                'id' => new IntegerSequence(),
                'name' => new StringSequence('name{n}')
            )
        );

        self::$pdo->exec('CREATE TABLE user(id INTEGER, name);');

        /**
         * @var \Fabrika\Producer\Fake\User $user1
         * @var \Fabrika\Producer\Fake\User $user2
         */
        $user1 = $producer->create();
        $user2 = $producer->create();
        $this->assertInstanceOf($modelClass, $user2);
        $this->assertEquals('name1', $user1->name);
        $this->assertEquals('name2', $user2->name);

        self::$pdo->exec('DELETE FROM user');
        self::$pdo->exec('DROP TABLE user');
    }

    public function testDelete()
    {
        $tableName = 'user';
        $modelClass = 'Fabrika\Producer\Fake\User';
        $producer = new ModelProducer(self::$pdo, $tableName, $modelClass);

        $producer->setDefinition(
            array(
                'id' => new IntegerSequence(),
                'name' => new StringSequence('name{n}')
            )
        );

        self::$pdo->exec('CREATE TABLE user(id INTEGER, name);');
        self::$pdo->exec('INSERT INTO user(id, name) VALUES(1, "name1")');
        self::$pdo->exec('INSERT INTO user(id, name) VALUES(2, "name2")');

        $this->assertTrue($producer->delete(1));
        $this->assertTrue($producer->delete(2));
        $this->assertFalse($producer->delete(1));

        self::$pdo->exec('DELETE FROM user');
        self::$pdo->exec('DROP TABLE user');
    }

    public function testFlush()
    {
        $tableName = 'user';
        $modelClass = 'Fabrika\Producer\Fake\User';
        $producer = new ModelProducer(self::$pdo, $tableName, $modelClass);

        $producer->setDefinition(
            array(
                'id' => new IntegerSequence(),
                'name' => new StringSequence('name{n}')
            )
        );

        self::$pdo->exec('CREATE TABLE user(id INTEGER, name);');
        self::$pdo->exec('INSERT INTO user(id, name) VALUES(1, "name1")');
        self::$pdo->exec('INSERT INTO user(id, name) VALUES(2, "name2")');

        $this->assertEquals(2, $producer->flush());

        self::$pdo->exec('DROP TABLE user');
    }

    public function testExcludedFields()
    {
        $tableName = 'user';
        $modelClass = 'Fabrika\Producer\Fake\UserExtraField';
        $producer = new ModelProducer(self::$pdo, $tableName, $modelClass);
        $producer->setExcludedFields(array('excluded'));

        $producer->setDefinition(
            array(
                'id' => new IntegerSequence(),
                'name' => new StringSequence('name{n}')
            )
        );

        self::$pdo->exec('CREATE TABLE user(id INTEGER, name, excluded);');
        $user = $producer->create();
        $this->assertObjectHasAttribute('excluded', $user);

        $producer->flush();
    }

    public function testFlushMustRestSequenceCounters()
    {

    }
}
