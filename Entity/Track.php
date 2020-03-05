<?php

namespace App\Entity;

class Track
{
    protected $_time;
    protected $_currentFloor;
    protected $_totalFloors;

    public function __construct(string $time, int $currentFloor = 0, int $totalFloors = 0)
    {
        $this->_time = $time;
        $this->_currentFloor = $currentFloor;
        $this->_totalFloors = $totalFloors;
    }

    public function time()
    {
        return $this->_time;
    }

    public function currentFloor()
    {
        return $this->_currentFloor;
    }

    public function totalFloors()
    {
        return $this->_totalFloors;
    }

    public function setTime(string $time)
    {
        $this->_time = $time;
    }

    public function setCurrentFloor(int $currentFloor)
    {
        $this->_currentFloor = $currentFloor;
    }
}