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
     *
     * @SWG\Post(
     * summary="Get auth token",
     * description="",
     * produces={"application/json"},
     * @SWG\Response(
     *     response=200,
     *     description="Return auth token",
     *   )
     * )
     *
     * @SWG\Parameter(
     *     name="username",
     *     in="query",
     *     type="string",
     *     description="email adress"
     * )
     * @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     type="string",
     *     description="password"
     * )
     *
     * @SWG\Tag(name="User")
     */
    public function login()
    {
        return new JsonResponse(['user' => $this->getUser()]);
    }
}
