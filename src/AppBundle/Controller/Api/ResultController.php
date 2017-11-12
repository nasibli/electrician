<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Manager\ResultManager;
use AppBundle\Manager\ElectricianManager;

class ResultController extends Controller {
    
    private $resultManager;
    private $sessionService;
    private $electricianManager;
    
    public function __construct(
            ElectricianManager $electricianManager,
            ResultManager $resultManager, 
            Session $sessionService
    ) {
        $this->electricianManager = $electricianManager;
        $this->resultManager      = $resultManager;
        $this->sessionService     = $sessionService;
    }
    
    /**
     * @Route ("/result", name="result.get")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getAction(Request $request) {
        $results = $this->resultManager->getBest();
        return new JsonResponse(['results' => $results]);
    }
    
    /**
     * @Route ("/result", name="result.post")
     * @Method({"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function postAction(Request $request) {
        $userName = $request->get('userName');
        $stepCount = $this->sessionService->get('stepCount');
        $this->resultManager->add($userName, $stepCount);
        $this->electricianManager->init();
        return new JsonResponse(['success' => true]);
    }
    
}
