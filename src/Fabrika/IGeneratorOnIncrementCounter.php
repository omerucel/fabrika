<?php

namespace Fabrika;

interface IGeneratorOnIncrementCounter
{
    /**
     * @param int $step
     * @return mixed
     */
    public function onIncrementCounter($step = 1);
}
