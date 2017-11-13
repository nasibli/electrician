<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Manager\ElectricianManager;
use AppBundle\Validator\ElectricianValidator;

class ElectricianController extends Controller{
    
    private $electricianManager;
    private $electricianValidator;
    
    public function __construct(ElectricianManager $electricianManager, ElectricianValidator $electricianValidator) {
        $this->electricianManager   = $electricianManager;
        $this->electricianValidator = $electricianValidator;
    }
    
    /**
     * @Route ("/electrician", name="electrician.post")
     * @Method({"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function stepAction(Request $request) {
        $rowIndex = $request->get('rowIndex');
        $colIndex = $request->get('colIndex');
        
        if (!$this->electricianValidator->validate($rowIndex, $colIndex)) {
            return new JsonResponse ([
                'errors' => $this->electricianValidator->getErrors()
            ]);
        }
        
        $result = [
            'cells'       => $this->electricianManager->step($rowIndex, $colIndex),
            'stepCount'   => $this->electricianManager->getStepCount(),
            'isCompleted' => $this->electricianManager->isCompleted()
        ];
                
        return new JsonResponse($result);
    }
    
}
