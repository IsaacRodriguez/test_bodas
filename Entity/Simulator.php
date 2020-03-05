<?php

namespace App\Entity;

class Simulator
{
    protected $_from;
    protected $_to;
    protected $_interval;

    public function __construct($from, $to, $interval)
    {
        $this->_from = $from;
        $this->_to= $to;
        $this->_interval= $interval;
    }

    public function from() 
    {
        return $this->_from;
    }

    public function to() 
    {
        return $this->_to;
    }

    public function interval() 
    {
        return $this->_interval;
    }
}