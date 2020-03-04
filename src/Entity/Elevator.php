<?php

namespace App\Entity;


class Elevator
{
    protected $_name;
    protected $_track = [];

    /**
     * string $name
     * Track[] $track
     */
    public function __construct(string $name, $track = [])
    {
        $this->_name = $name;
        $this->_track = $track;
    }

    public function name(): string
    {
        return $this->_name;
    }

    public function track(): array
    {
        return $this->_track;
    }

    public function addTrack(Track $track)
    {
        $this->_track[] = $track;
    }

    public function getLastTrack() {
        $lastTrack = null;
        if (empty($this->_track)) {
            return null;
        }

        $lastTrack = $this->_track[0];

        foreach($this->_track as $currentTrack) {
            if ($currentTrack->totalFloors() > $lastTrack->totalFloors()) {
                $lastTrack = $currentTrack;
            }
        }

        return $lastTrack;
    }
}