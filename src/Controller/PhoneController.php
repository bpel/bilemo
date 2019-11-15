<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use App\Service\Pagination;
use Hateoas\HateoasBuilder;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class PhoneController extends AbstractController
{
    /**
     * @Route("/api/phones", methods={"GET"})
     * @return Response
     *
     * @SWG\Get(
     * summary="Get phone list",
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
     *     description="Return phone list",
     *     @SWG\Schema(ref=@Model(type=Phone::class))
     * ),
     * @SWG\Response(
     *     response=401,
     *     description="JWT Token not found or expired",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Phone not found",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Server error",
     * )
     * )
     *
     * @SWG\Tag(name="Phone")
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getPhones(Request $request, PhoneRepository $phoneRepository, CacheInterface $cache, Pagination $pagination)
    {
        $page = $request->query->get('page');
        $limit = $request->query->get('limit');

        if(!$pagination->isValidParameters($page, $limit))
        {
            return new JsonResponse(['code' => 400, 'message' => 'Bad parameters for pagination'], Response::HTTP_BAD_REQUEST);
        }

        $phones = $cache->get('phones-list-p'.$page.'-l'.$limit, function (ItemInterface $item) use($phoneRepository, $page, $limit) {
            $item->expiresAfter($this->getParameter("cache.expiration"));

            return $phoneRepository->findAllPhones($page, $limit);
        });

        if (empty($phones)) {
            return new JsonResponse(['code' => 404, 'message' => 'Phone not found'], Response::HTTP_NOT_FOUND);
        }

        $hateoas = HateoasBuilder::create()->build();

        $data = $hateoas->serialize($phones, 'json');

        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/api/phones/{id}", methods={"GET"})
     * @param $id
     * @return Response
     *
     * @SWG\Get(
     * summary="Get phone detail",
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
     *     description="Return phone detail",
     *     @SWG\Schema(ref=@Model(type=Phone::class))
     * ),
     * @SWG\Response(
     *     response=401,
     *     description="JWT Token not found or expired",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Phone not found",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Server error",
     * )
     * )
     * @SWG\Tag(name="Phone")
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getPhone(PhoneRepository $phoneRepository, CacheInterface $cache, $id)
    {
        $phone = $cache->get('phone-detail-'.$id, function (ItemInterface $item) use ($phoneRepository, $id){
            $item->expiresAfter($this->getParameter("cache.expiration"));

            return $phoneRepository->findOneBy(['id' => $id]);
        });

        if (empty($phone)) {
            return new JsonResponse(['code' => 404, 'message' => 'Phone not found for id = '.$id], Response::HTTP_NOT_FOUND);
        }

        $hateoas = HateoasBuilder::create()->build();

        $data = $hateoas->serialize($phone, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
