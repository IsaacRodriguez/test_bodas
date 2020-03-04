<?php

namespace App\Controller;

use App\Common\Simulation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sequence;
use App\Entity\Elevator;
use App\Entity\Simulator;

class SimulatorController extends AbstractController
{

    protected $_sequences = [];
    protected $_elevators = [];
    protected $_simulator;


    /**
     * @Route("/", name="simulator")
     */
    public function index()
    {

        $this->_sequences[] = new Sequence('9:00','11:00', 5, [0], [2]);
        $this->_sequences[] = new Sequence('9:00','11:00', 10, [0], [1]);
        $this->_sequences[] = new Sequence('11:00','18:20', 20, [0], [1,2,3]);
        $this->_sequences[] = new Sequence('14:00','15:00', 4, [1,2,3], [0]);

        $this->_elevators[] = new Elevator('Elevator 1');
        $this->_elevators[] = new Elevator('Elevator 2');
        $this->_elevators[] = new Elevator('Elevator 3');

        $this->_simulator = new Simulator('09:00', '20:00', 1);

        $simulation = new Simulation($this->_simulator, $this->_elevators, $this->_sequences);

        return $this->render('simulator/index.html.twig', [
            'controller_name' => 'SimulatorController',
            'simulation' => $simulation->run()
        ]);
    }
}
