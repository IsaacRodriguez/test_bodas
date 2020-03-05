<?php
namespace App\Entity;

class Sequence
{
    protected $_startTime;
    protected $_endTime;
    protected $_interval;
    protected $_from;
    protected $_to;

    public function __construct($startTime, $endTime, $interval, $from, $to)
    {
        $this->_startTime = $startTime;
        $this->_endTime = $endTime;
        $this->_interval = $interval;
        $this->_from = $from;
        $this->_to = $to;        
    }

    public function startTime()
    {
        return $this->_startTime;
    }

    public function endTime()
    {
        return $this->_endTime;
    }

    public function interval()
    {
        return $this->_interval;
    }

    public function from()
    {
        return $this->_from;
    }

    public function to()
    {
        return $this->_to;
    }
}