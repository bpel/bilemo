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
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validation;
use Hateoas\HateoasBuilder;

class UserController extends AbstractController
{
    /**
     * @Route("/api/users", methods={"GET"})
     * @return Response
     *
     * @SWG\Get(
     * summary="Get user list",
     * description="",
     * produces={"application/json"},
     * @SWG\Response(
     *     response=200,
     *     description="Return user list",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=User::class, groups={"full"}))
     *     )
     *   )
     * )
     * @SWG\Tag(name="User")
     */
    public function getUsers()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        if (empty($users)) {
            return new JsonResponse(['status' => '404', 'message' => 'no users have been found'], Response::HTTP_NOT_FOUND);
        }

        $hateoas = HateoasBuilder::create()->build();

        $data = $hateoas->serialize($users, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/api/users/{id}", methods={"GET"})
     * @param $id
     * @return Response
     *
     * @SWG\Get(
     * summary="Get user detail",
     * description="",
     * produces={"application/json"},
     * @SWG\Response(
     *     response=200,
     *     description="Return user detail",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=User::class, groups={"full"}))
     *     )
     *   )
     * )
     * @SWG\Tag(name="User")
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
     * @Route("/api/users/enterprise/{id}", methods={"GET"})
     * @param $id
     * @return Response
     *
     * @SWG\Get(
     * summary="",
     * description="blaldfdd",
     * produces={"application/json"},
     * @SWG\Response(
     *     response=200,
     *     description="Return list user per enterprise",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=User::class, groups={"full"}))
     *     )
     *   )
     * )
     * @SWG\Tag(name="User")
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
     * @Route("/api/users", methods={"POST"})
     * @return Response
     *
     * @SWG\Post(
     * summary="Create new user",
     * description="",
     * produces={"application/json"},
     * @SWG\Response(
     *     response=200,
     *     description="User created",
     *   )
     * )
     * @SWG\Tag(name="User")
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
     * @Route("/api/users", methods={"DELETE"})
     * @return Response
     *
     * @SWG\Delete(
     * summary="Delete user",
     * description="",
     * produces={"application/json"},
     * @SWG\Response(
     *     response=200,
     *     description="User deleted",
     *   )
     * )
     * @SWG\Tag(name="User")
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

    /**
     * @Route("/api/login_check", methods={"POST"})
     * @return Response
     */
    public function login()
    {
        return new JsonResponse(['user' => $this->getUser()], Response::HTTP_NOT_FOUND);
    }
}