<?php

namespace Fabrika;

class Field
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var IGenerator
     */
    protected $generator;

    /**
     * @param string $name
     * @param string|IGenerator $generator
     */
    public function __construct($name, $generator = '')
    {
        $this->name = $name;
        $this->setGenerator($generator);
    }

    /**
     * @param $generator
     * @return $this
     */
    public function setGenerator($generator)
    {
        if (!$generator instanceof IGenerator) {
            $generator = new Generator\StringSequence(strval($generator));
        }

        $this->generator = $generator;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return IGenerator
     */
    public function getGenerator()
    {
        return $this->generator;
    }
}