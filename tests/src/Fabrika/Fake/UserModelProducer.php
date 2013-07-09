<?php

namespace Fabrika\Fake;

use Fabrika\Generator\IntegerSequence;
use Fabrika\Generator\StringSequence;
use Fabrika\ModelProducerProxyAbstract;

class UserModelProducer extends ModelProducerProxyAbstract
{
    protected static $tableName = 'user';
    protected static $modelClass = '\Fabrika\Producer\Fake\User';

    /**
     * @return array
     */
    public static function getDefinition()
    {
        return array(
            'id' => new IntegerSequence(),
            'name' => new StringSequence('name{n}')
        );
    }
}
