<?php

namespace Fabrika\Producer\Database\Connection;

class MySQLTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PDO
     */
    protected static $pdo;

    public static function setUpBeforeClass()
    {
        if (self::$pdo == null) {
            self::$pdo = new \PDO(
                $GLOBALS['DB_MYSQL_DSN'],
                $GLOBALS['DB_MYSQL_USER'],
                $GLOBALS['DB_MYSQL_PASS']
            );
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
    }

    public function setUp()
    {
        self::$pdo->exec('CREATE TABLE IF NOT EXISTS `user`(`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `username` varchar(100) DEFAULT NULL, PRIMARY KEY (`id`));');
    }

    public function tearDown()
    {
        self::$pdo->exec('DROP TABLE `user`;');
    }

    public function testInsert()
    {
        $data = array(
            'id' => 1,
            'username' => 'username'
        );
        $connection = new MySQL(self::$pdo);
        $affectedRows = $connection->insert('user', $data);
        $this->assertEquals(1, $affectedRows);
    }

    public function testFlush()
    {
        $connection = new MySQL(self::$pdo);

        $data1 = array(
            'id' => 1,
            'username' => 'username1'
        );
        $data2 = array(
            'id' => 2,
            'username' => 'username2'
        );
        $data3 = array(
            'id' => 3,
            'username' => 'username3'
        );
        $connection->insert('user', $data1);
        $connection->insert('user', $data2);
        $connection->insert('user', $data3);

        $stmt = self::$pdo->prepare('SELECT COUNT(*) AS count FROM user');
        $stmt->execute();
        $count = $stmt->fetchColumn();
        $stmt->closeCursor();

        $this->assertEquals(3, $count);

        $connection->flush('user');

        $stmt = self::$pdo->prepare('SELECT COUNT(*) AS count FROM user');
        $stmt->execute();
        $count = $stmt->fetchColumn();
        $stmt->closeCursor();

        $this->assertEquals(0, $count);
    }
}
