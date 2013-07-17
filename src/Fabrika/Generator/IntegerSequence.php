<?php

namespace Fabrika\Generator;

use Fabrika\IGenerator;
use Fabrika\IGeneratorOnFlush;
use Fabrika\IGeneratorOnIncrementCounter;

class IntegerSequence implements IGenerator, IGeneratorOnFlush, IGeneratorOnIncrementCounter
{
    /**
     * @var int
     */
    protected $counter = 0;

    /**
     * @var int
     */
    protected $step = 1;

    /**
     * @param int $step
     */
    public function __construct($step = 1)
    {
        $this->step = $step;
    }

    /**
     * @return mixed
     */
    public function generate()
    {
        return $this->counter = $this->counter + $this->step;
    }

    public function onFlush()
    {
        $this->counter = 0;
    }

    public function onIncrementCounter($step = 1)
    {
        if ($step < 1) {
            $step = 1;
        }

        $this->counter = $this->counter + $step;
    }
}
