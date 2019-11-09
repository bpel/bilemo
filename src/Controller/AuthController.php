<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * @Route("/api/login_check", methods={"POST"})
     * @return Response
     * @SWG\Tag(name="Auth")
     */
    public function login()
    {
        return new JsonResponse(['user' => $this->getUser()]);
    }
}
