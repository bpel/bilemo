<?php

namespace App\Controller;

use App\Form\UserType;
use Nelmio\ApiDocBundle\Annotation\Model;
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
     *
     * @SWG\Parameter(
     *     name="username",
     *     in="body",
     *     description="email adress",
     *     required=true,
     *     type="string",
     *     @SWG\Schema(
     *     type="array",
     *      @SWG\Items(
     *        type="object",
     *        @SWG\Property(property="username", type="string", example="demo@test.fr")
     *      )
     *     )
     * ),
     *
     * @SWG\Parameter(
     *     name="password",
     *     in="body",
     *     description="password",
     *     required=true,
     *     type="string",
     *     @SWG\Schema(
     *     type="array",
     *      @SWG\Items(
     *        type="object",
     *        @SWG\Property(property="password", type="string", example="123456789")
     *      )
     *     )
     * ),
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return auth token",
     *   ),
     * @SWG\Response(
     *     response=400,
     *     description="Bad request",
     *   ),
     * @SWG\Response(
     *     response=401,
     *     description="Bad credentials",
     *   ),
     * @SWG\Response(
     *     response=500,
     *     description="Server error",
     *   )
     * )
     *
     * @SWG\Tag(name="User")
     */
    public function login()
    {
        return new JsonResponse(['user' => $this->getUser()]);
    }
}
