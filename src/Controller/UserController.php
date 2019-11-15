<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Hateoas\HateoasBuilder;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/api/users", methods={"GET"})
     * @return Response
     *
     * @SWG\Get(
     * summary="Get user list",
     * produces={"application/json"},
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="Bearer jwt",
     *     description="Authorization token required to access resources"
     * ),
     *
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer",
     *     description="page number"
     * ),
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     description="number items per page"
     * ),
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return user list",
     *     @SWG\Schema()
     * ),
     * @SWG\Response(
     *     response=400,
     *     description="Bad parameters for pagination",
     * ),
     * @SWG\Response(
     *     response=401,
     *     description="JWT Token not found or expired",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="User not found",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Server error",
     * )
     * )
     *
     * @SWG\Tag(name="User")
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getUsers(Request $request, UserRepository $userRepository, CacheInterface $cache, Pagination $pagination)
    {
        $page = $request->query->get('page');
        $limit = $request->query->get('limit');

        if(!$pagination->isValidParameters($page, $limit))
        {
            return new JsonResponse(['code' => 400, 'message' => 'Bad parameters for pagination'], Response::HTTP_BAD_REQUEST);
        }

        $users = $cache->get('users-list-p'.$page.'-l'.$limit, function (ItemInterface $item) use ($userRepository, $page, $limit){
            $item->expiresAfter($this->getParameter("cache.expiration"));
            return $userRepository->findAllUsers($page, $limit);
        });

        if (empty($users)) {
            return new JsonResponse(['code' => 404, 'message' => 'User not found'], Response::HTTP_NOT_FOUND);
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
     * produces={"application/json"},
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="Bearer jwt",
     *     description="Authorization token required to access resources"
     * ),
     *
     * @SWG\Response(
     *     response=200,
     *     description="Return user detail",
     *     @SWG\Schema()
     * ),
     * @SWG\Response(
     *     response=401,
     *     description="JWT Token not found or expired",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="User not found",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Server error",
     * )
     * )
     *
     * @SWG\Tag(name="User")
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getUserDetail(UserRepository $userRepository, $id, CacheInterface $cache)
    {
        $user = $cache->get('user-detail-'.$id, function (ItemInterface $item) use ($id, $userRepository) {
            $item->expiresAfter($this->getParameter("cache.expiration"));

            return $userRepository->findOneBy(['id' => $id]);
        });

        if (empty($user)) {
            return new JsonResponse(['code' => 404, 'message' => 'User not found for id = '.$id], Response::HTTP_NOT_FOUND);
        }

        $hateoas = HateoasBuilder::create()->build();

        $data = $hateoas->serialize($user, 'json');

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
     * produces={"application/json"},
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="Bearer jwt",
     *     description="Authorization token required to access resources"
     * ),
     *
     * @SWG\Parameter(
     *     name="user",
     *     in="body",
     *     description="data to create new user",
     *     required=true,
     *     type="array",
     *     @SWG\Schema()
     * ),
     *
     * @SWG\Response(
     *     response=200,
     *     description="User created",
     * ),
     * @SWG\Response(
     *     response=401,
     *     description="JWT Token not found or expired",
     * ),
     * @SWG\Response(
     *     response=400,
     *     description="Fields are not valid.",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Server error",
     * )
     * )
     *
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
            return new JsonResponse(['code' => 201, 'message' => 'User created'], Response::HTTP_OK);
        }

        return new JsonResponse(['code' => 400, 'message' => 'Fields are not valid.'], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/api/users/{id}", methods={"DELETE"})
     * @return Response
     *
     * @SWG\Delete(
     * summary="Delete user",
     * produces={"application/json"},
     *
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="Bearer jwt",
     *     description="Authorization token required to access resources"
     * ),
     *
     * @SWG\Response(
     *     response=200,
     *     description="User deleted",
     * ),
     * @SWG\Response(
     *     response=401,
     *     description="JWT Token not found or expired",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="User not found",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Server error",
     * )
     * )
     *
     * @SWG\Tag(name="User")
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function deleteUser($id, CacheInterface $cache)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['id' => $id]);

        if (empty($user)) {
            return new JsonResponse(['code' => 404, 'message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($user);
        $em->flush();

        $cache->delete('user-detail-'.$id);

        return new JsonResponse(['code' => 204, 'message' => 'User deleted'], Response::HTTP_NO_CONTENT);
    }
}