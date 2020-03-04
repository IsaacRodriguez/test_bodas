<?php

namespace App\Common;

use App\Entity\Simulator;
use App\Entity\Elevator;
use App\Entity\Track;
use App\Entity\Sequence;

class Simulation
{
    /**
     * @var Simulator
     */
    private $_simulator = null;


    /**
     * @var Sequence[]
     */
    protected $_sequences = [];

    /**
     * @var Elevator[]
     */
    protected $_elevators = [];

    /**
     * @var Simulator $simulator
     * @var Elevator[] $elevators
     * @var Sequence[] $sequences
     */
    public function __construct($simulator, $elevators, $sequences)
    {
        $this->_sequences = $sequences;
        $this->_elevators = $elevators;
        $this->_simulator = $simulator;
    }

    public function run()
    {
        $elevators = $this->_elevators;
        $currentTime = new \DateTime($this->_simulator->from());
        $to = new \DateTime($this->_simulator->to());
        $interval = \DateInterval::createFromDateString($this->_simulator->interval() . ' minute');

        for ($currentTime; $currentTime <= $to; $currentTime->add($interval)) {
            $this->_checkSequences($currentTime);
        }

        return $this->_elevators;
    }

    protected function _checkSequences(\DateTime $currentTime) {
        foreach($this->_sequences as $sequence) {
            $this->_checkSequence($currentTime, $sequence);
        }
    }

    protected function _checkSequence(\DateTime $currentTime, Sequence $sequence) {
        $startTime = new \DateTime($sequence->startTime());
        $endTime = new \DateTime($sequence->endTime());
        $interval = \DateInterval::createFromDateString($sequence->interval() . ' minute');
        $sequenceTime = $startTime;

        if ($currentTime < $startTime || $currentTime > $endTime) {
            return null;
        }

        for ($sequenceTime; $sequenceTime <= $currentTime; $sequenceTime->add($interval)) {
            if ($sequenceTime == $currentTime) {
                $this->_addTrack($sequenceTime, $sequence);
            }
        }
    }

    protected function _addTrack(\DateTime $currentTime, Sequence $sequence) {
        foreach($this->_elevators as $keyElevator => $elevator) {
            $currentTimeFormatted = $currentTime->format('H:i');
            $lastTrack = $elevator->getLastTrack();
            $track = new Track($currentTimeFormatted, $sequence->from()[0], $sequence->to()[0]);
            $this->_elevators[$keyElevator]->addTrack($track);
        }
    }


}