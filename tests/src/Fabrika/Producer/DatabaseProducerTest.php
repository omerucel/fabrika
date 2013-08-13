<?php

namespace Fabrika\Producer\Database\MySQL;

use Fabrika\Generator\AutoIncrement;
use Fabrika\Generator\StringSequence;
use Fabrika\Producer\Database\Connection\Dummy;
use Fabrika\Producer\DatabaseProducer;

class DatabaseProducerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DatabaseProducer
     */
    protected $producer;

    public function setUp()
    {
        $connection = new Dummy();
        $this->producer = new DatabaseProducer($connection);
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf('Fabrika\ProducerAbstract', $this->producer);
    }

    public function testCreate()
    {
        $this->producer->define('user')
            ->addField('id', new AutoIncrement())
            ->addField('username', new StringSequence('username{n}'));

        $user1 = $this->producer->create('user');
        $user2 = $this->producer->create('user');
        $user3 = $this->producer->create(
            'user',
            array(
                'username' => 'test'
            )
        );

        $this->assertEquals(1, $user1->id);
        $this->assertEquals('username1', $user1->username);
        $this->assertEquals(2, $user2->id);
        $this->assertEquals('username2', $user2->username);
        $this->assertEquals(3, $user3->id);
        $this->assertEquals('test', $user3->username);
    }

    public function testFlush()
    {
        $this->producer->define('user')
            ->addField('id', new AutoIncrement())
            ->addField('username', new StringSequence('username{n}'));

        $this->producer->create('user');
        $user = $this->producer->create('user');
        $this->assertEquals(2, $user->id);
        $this->producer->flush('user');

        $user = $this->producer->create('user');
        $this->assertEquals(1, $user->id);
    }

    public function testGetRowDataFromObject()
    {
        $object = new \stdClass();
        $object->id = 1;
        $object->username = 'username';

        $columns = $this->producer->getRowDataFromObject($object);
        $this->assertArrayHasKey('id', $columns);
        $this->assertArrayHasKey('username', $columns);
    }
}
