<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

class UserController extends AbstractController
{
    /**
     * @Rest\Get("api/user/list")
     */
    public function getUsers()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        if (empty($users)) {
            return new JsonResponse(['message' => 'no users have been found'], Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('serializer')->serialize($users, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Get("api/user/detail/{id}")
     */
    public function getUserDetail($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findUserById($id);

        if (empty($user)) {
            return new JsonResponse(['message' => 'this user does not exist'], Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('serializer')->serialize($user, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Get("api/user/enterprise/{nameEnterprise}")
     */
    public function getUsersByEnterprise($nameEnterprise)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findUsersByEnterprise($nameEnterprise);

        if (empty($users)) {
            return new JsonResponse(['message' => 'no users for this enterprise'], Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('serializer')->serialize($users, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Get("api/user/add/")
     * @Rest\Post("api/user/add/")
     */
    public function addUser()
    {
        return new JsonResponse(['message' => 'add'], Response::HTTP_ACCEPTED);
    }

    /**
     * @Rest\Get("api/user/delete/")
     * @Rest\Post("api/user/delete/")
     */
    public function deleteUser()
    {
        return new JsonResponse(['message' => 'delete'], Response::HTTP_ACCEPTED);
    }
}
