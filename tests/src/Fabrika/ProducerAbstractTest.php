<?php

namespace Fabrika;

use Fabrika\Generator\AutoIncrement;
use Fabrika\Generator\StringSequence;

class ProducerAbstractTest extends \PHPUnit_Framework_TestCase
{
    public function testInstanceOf()
    {
        $producer = $this->getMockBuilder('Fabrika\ProducerAbstract')
            ->getMockForAbstractClass();
        $this->assertInstanceOf('Fabrika\IProducer', $producer);
    }

    public function testDefine()
    {
        $producer = $this->getMockBuilder('Fabrika\ProducerAbstract')
            ->getMockForAbstractClass();

        /**
         * @var ProducerAbstract $producer
         */
        $producer->define('user')
            ->addField('id', new AutoIncrement())
            ->addField('username', new StringSequence('username{n}'));

        $this->assertTrue($producer->hasDefinition('user'));
        $this->assertInstanceOf('Fabrika\Definition', $producer->getDefinition('user'));
        $this->assertTrue($producer->getDefinition('user')->hasField('username'));

        $definition = new Definition('book');
        $definition->addField('id', new AutoIncrement())
            ->addField('title', new StringSequence('title{n}'));

        $producer = $this->getMockBuilder('Fabrika\ProducerAbstract')
            ->getMockForAbstractClass();

        /**
         * @var ProducerAbstract $producer
         */
        $producer->define($definition);

        $this->assertTrue($producer->hasDefinition('book'));
        $this->assertInstanceOf('Fabrika\Definition', $producer->getDefinition('book'));
        $this->assertTrue($producer->getDefinition('book')->hasField('title'));
    }

    public function testBuildObject()
    {
        $producer = $this->getMockBuilder('Fabrika\ProducerAbstract')
            ->getMockForAbstractClass();

        /**
         * @var ProducerAbstract $producer
         */
        $fields = array(
            'id' => new AutoIncrement(),
            'username' => new StringSequence('username{n}')
        );
        $object1 = $producer->buildObject($fields);
        $this->assertInstanceOf('\stdClass', $object1);

        $this->assertEquals(1, $object1->id);
        $this->assertEquals('username1', $object1->username);

        $object2 = $producer->buildObject($fields);
        $this->assertEquals(2, $object2->id);
        $this->assertEquals('username2', $object2->username);
    }

    public function testBuildCustomObject()
    {
        $producer = $this->getMockBuilder('Fabrika\ProducerAbstract')
            ->getMockForAbstractClass();

        /**
         * @var ProducerAbstract $producer
         */
        $fields = array(
            'id' => new AutoIncrement(),
            'username' => new StringSequence('username{n}')
        );
        $object = $producer->buildObject($fields, 'Fabrika\Fake\CustomObject');
        $this->assertInstanceOf('Fabrika\Fake\CustomObject', $object);
    }

    public function testBuild()
    {
        $producer = $this->getMockBuilder('Fabrika\ProducerAbstract')
            ->getMockForAbstractClass();

        /**
         * @var ProducerAbstract $producer
         */
        $producer->define('user')
            ->addField('id', new AutoIncrement())
            ->addField('username', new StringSequence('username{n}'));

        $object1 = $producer->build('user');
        $object2 = $producer->build('user');

        $this->assertEquals(1, $object1->id);
        $this->assertEquals('username1', $object1->username);

        $this->assertEquals(2, $object2->id);
        $this->assertEquals('username2', $object2->username);
    }

    public function testOnFlush()
    {
        $producer = $this->getMockBuilder('Fabrika\ProducerAbstract')
            ->getMockForAbstractClass();

        /**
         * @var ProducerAbstract $producer
         */
        $producer->define('user')
            ->addField('id', new AutoIncrement())
            ->addField('username', new StringSequence('username{n}'));

        $user1 = $producer->build('user');
        $user2 = $producer->build('user');

        $this->assertEquals(2, $user2->id);
        $producer->onFlush('user');

        $user3 = $producer->build('user');
        $this->assertEquals(1, $user3->id);
    }

    public function testOnIncrementCounters()
    {
        $producer = $this->getMockBuilder('Fabrika\ProducerAbstract')
            ->getMockForAbstractClass();

        /**
         * @var ProducerAbstract $producer
         */
        $producer->define('user')
            ->addField('id', new AutoIncrement())
            ->addField('username', new StringSequence('username{n}'));

        $user1 = $producer->build('user');

        $this->assertEquals(1, $user1->id);
        $producer->onIncrementCounters('user', 5);

        $user3 = $producer->build('user');
        $this->assertEquals(7, $user3->id);
        $this->assertEquals('username7', $user3->username);
    }
}
