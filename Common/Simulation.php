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
        $interval = \DateInterval::createFromDateString($this->_simulator->interval() . ' minutes');

        for ($currentTime; $currentTime <= $to; $currentTime->add($interval)) {

            foreach($elevators as $keyElevator => $elevator) {
                $newTrack = $this->_addTrack($keyElevator, $currentTime);
                $this->_elevators[$keyElevator]->addTrack($newTrack);
            }
        }

        return $this->_elevators;
    }

    protected function _addTrack($keyElevator, \DateTime $currentTime): Track
    {
        $currentTimeFormatted = $currentTime->format('H:i');
        $lastTrack = $this->_elevators[$keyElevator]->getLastTrack();

        if (empty($lastTrack)) {
            $currentFloor = 0;
            $totalFloor = 0;
        } else {
            $currentFloor = $lastTrack->currentFloor();
            $totalFloor = $lastTrack->totalFloors();
        }

        $newTrack = new Track($currentTimeFormatted, $currentFloor, $totalFloor);

        foreach($this->_sequences as $sequence) {
            $startTime = new \DateTime($sequence->startTime());
            $endTime = new \DateTime($sequence->endTime());
            $interval = \DateInterval::createFromDateString($sequence->interval() . ' minutes');

            for ($sequenceTime = $startTime; $sequenceTime <= $currentTime; $sequenceTime->add($interval)) {
                if ($sequenceTime == $currentTime) {

                    $sequenceFrom = $sequence->from();
                    $sequenceTo = $sequence->to();
                    $floorsMoved = 0;
                    $waste = 0;
                    $modifyTrack = false;
                    if (in_array($currentFloor, $sequenceFrom)) {
                        $modifyTrack = true;
                        if (isset($sequenceTo[$keyElevator])) {
                            $to = $sequenceTo[$keyElevator];
                            $floorsMoved += abs($currentFloor - $sequenceTo[$keyElevator]);
                        } else {
                            $to = $sequenceTo[0];
                            $floorsMoved += abs($currentFloor - $sequenceTo[0]);
                        }

                        $waste = $totalFloor + $floorsMoved;
                        $newTrack = new Track($currentTimeFormatted, $to, $waste);

                    }
                }
            }
        }
        return $newTrack;
    }
}