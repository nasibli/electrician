<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Manager\ElectricianManager;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    private $electricianManager;
    
    public function __construct(ElectricianManager $electricianManager) {
        $this->electricianManager = $electricianManager;
    }
    
    /**
     * @Route("", name="main")
     */
    public function indexAction() {
        $this->electricianManager->init();
        $data = [
            'board' => $this->electricianManager->getBoard()
        ];
        return $this->render('AppBundle:default:index.html.twig', $data);
    }
    
}
