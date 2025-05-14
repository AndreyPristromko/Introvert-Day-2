<?php

namespace App\Controller;

use App\Service\LeadsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LeadsController extends AbstractController
{
    private LeadsService $leadsService;

    public function __construct(LeadsService $leadsService)
    {
        $this->leadsService = $leadsService;
    }

    public function checkData(Request $request): JsonResponse
    {
        $timestamp = $request->query->get('date');
        $leadsDay = $this->leadsService->getLeadsByDate($timestamp);
        
        return new JsonResponse([
            'success' => true,
            'data' => $leadsDay
        ]);
    }
}
