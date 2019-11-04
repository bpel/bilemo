<?php

namespace App\Controller;

use App\Entity\Enterprise;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validation;
use Hateoas\HateoasBuilder;

class UserController extends AbstractController
{
    /**
     * @Rest\Get("api/users")
     */
    public function getUsers()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        if (empty($users)) {
            return new JsonResponse(['message' => 'no users have been found'], Response::HTTP_NOT_FOUND);
        }

        $hateoas = HateoasBuilder::create()->build();

        $data = $hateoas->serialize($users, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Get("api/users/{id}")
     */
    public function getUserDetail($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findUserById($id);

        if (empty($user)) {
            return new JsonResponse(['message' => 'this user does not exist'], Response::HTTP_NOT_FOUND);
        }

        $hateoas = HateoasBuilder::create()->build();

        $data = $hateoas->serialize($user, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Get("api/users/enterprise/{id}")
     */
    public function getUsersByEnterprise($id)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findUsersByEnterprise($id);

        if (empty($users)) {
            return new JsonResponse(['message' => 'no users for this enterprise'], Response::HTTP_NOT_FOUND);
        }

        $hateoas = HateoasBuilder::create()->build();

        $data = $hateoas->serialize($users, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Post("api/users")
     */
    public function addUser(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $user->getPassword();
            $encoded = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);

            $manager->persist($user);
            $manager->flush();
            return new JsonResponse(['message' => 'user was successfully created'], Response::HTTP_OK);
        }
        return new JsonResponse(['message' => 'One or multiple fields are not valid.'], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Delete("api/users/{id}")
     */
    public function deleteUser($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['id' => $id]);

        if (empty($user)) {
            return new JsonResponse(['message' => 'this user does not exist'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($user);
        $em->flush();

        return new JsonResponse(['message' => 'user was successfully deleted'], Response::HTTP_OK);
    }
}
