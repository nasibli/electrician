<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Manager\ElectricianManager;

class ElectricianController extends Controller{
    
    private $electricianManager;

    
    public function __construct(ElectricianManager $electricianManager) {
        $this->electricianManager = $electricianManager;
    }
    
    /**
     * @Route ("/electrician", name="electrician.post")
     * @Method({"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function stepAction(Request $request) {
        $result = [
            'cells' => $this->electricianManager->step(
                $request->request->get('rowIndex'), 
                $request->request->get('colIndex')
             ),
            'stepCount'   => $this->electricianManager->getStepCount(),
            'isCompleted' => $this->electricianManager->isCompleted()
        ];
        return new JsonResponse($result);
    }
    
}
