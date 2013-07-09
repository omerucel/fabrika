<?php

namespace Fabrika;

use Fabrika\Fake\UserModelProducer;

class ModelProducerProxyAbstractTest extends \PHPUnit_Framework_TestCase
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

    public function testInit()
    {
        try {
            ModelProducerProxyAbstract::init(self::$pdo);
        } catch (\Exception $e) {
            $this->fail($e);
        }
    }

    public function testBuild()
    {
        // build method does not execute sql queries.
        $user1 = UserModelProducer::build();
        $user2 = UserModelProducer::build();
        $this->assertEquals(1, $user1->id);
        $this->assertEquals(2, $user2->id);

        UserModelProducer::flush();
    }

    public function testCreate()
    {
        // create method executes sql queries. at first, you must create table.
        self::$pdo->exec('CREATE TABLE IF NOT EXISTS user(id INTEGER, name)');

        $user1 = UserModelProducer::create();
        $user2 = UserModelProducer::create();
        $this->assertEquals(1, $user1->id);
        $this->assertEquals(2, $user2->id);

        $stmt = self::$pdo->prepare('SELECT COUNT(*) AS count FROM user');
        $stmt->execute();
        $this->assertEquals(2, $stmt->fetchColumn(0));

        UserModelProducer::flush();
    }

    public function testDelete()
    {
        self::$pdo->exec('CREATE TABLE IF NOT EXISTS user(id INTEGER, name)');

        $user1 = UserModelProducer::create();
        $user2 = UserModelProducer::create();
        $this->assertEquals(1, $user1->id);
        $this->assertEquals(2, $user2->id);

        UserModelProducer::delete($user1->id);

        $stmt = self::$pdo->prepare('SELECT COUNT(*) AS count FROM user');
        $stmt->execute();
        $this->assertEquals(1, $stmt->fetchColumn(0));

        UserModelProducer::flush();
    }
}
