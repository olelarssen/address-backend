<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Log;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use App\Service\ApiService;

class ApiController extends AbstractController
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ApiService
     */
    private $service;

    public function __construct(RegistryInterface $doctrine, ApiService $service)
    {
        $this->em      = $doctrine->getEntityManager();
        $this->service = $service;
    }

    /**
     *
     * @Route("/", name="index")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     */
    public function index(Request $request)
    {
        try {
            if ($request->get('data')) {
                return $this->json($this->service->getData($request->get('data')));
            }

            return $this->json(['ping' => 'pong']);
        } catch (\Exception $e) {
            return $this->json([]);
        }
    }
}
