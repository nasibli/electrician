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
use AppBundle\Validator\ResultValidator;

class ResultController extends Controller {
    
    private $resultManager;
    private $electricianManager;
    private $resultValidator;
    
    public function __construct(
            ElectricianManager $electricianManager,
            ResultManager $resultManager, 
            ResultValidator $resultValidator
    ) {
        $this->electricianManager = $electricianManager;
        $this->resultManager      = $resultManager;
        $this->resultValidator    = $resultValidator;
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
        $stepCount = $this->electricianManager->getStepCount();
        
        if (!$this->resultValidator->validate($userName, $stepCount)) {
            return new JsonResponse([
                'success' => false,
                'errors'  => $this->resultValidator->getErrors()
            ]);
        }
        
        $this->resultManager->add($userName, $stepCount);
        $this->electricianManager->init();
        return new JsonResponse(['success' => true]);
    }
    
}
