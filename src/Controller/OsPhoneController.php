<?php

namespace App\Controller;

use App\Entity\OsPhone;
use App\Repository\OsPhoneRepository;
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

class OsPhoneController extends AbstractController
{
    /**
     * @Route("/api/os", methods={"GET"})
     * @return Response
     *
     * @SWG\Get(
     * summary="Get Os list",
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
     *     description="Return os phone list",
     *     @SWG\Schema(ref=@Model(type=OsPhone::class))
     * ),
     * @SWG\Response(
     *     response=401,
     *     description="JWT Token not found or expired",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Os not found",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Server error",
     * )
     * )
     * @SWG\Tag(name="Os")
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getOsPhones(Request $request, OsPhoneRepository $osPhoneRepository, CacheInterface $cache, Pagination $pagination)
    {
        $page = $request->query->get('page');
        $limit = $request->query->get('limit');

        if(!$pagination->isValidParameters($page, $limit))
        {
            return new JsonResponse(['code' => 400, 'message' => 'Bad parameters for pagination'], Response::HTTP_BAD_REQUEST);
        }

        $osPhones = $cache->get('osphones-list-p'.$page.'-l'.$limit, function (ItemInterface $item) use($osPhoneRepository, $page, $limit) {
            $item->expiresAfter($this->getParameter("cache.expiration"));

            return $osPhoneRepository->findAllOsPhones($page, $limit);
        });

        if (empty($osPhones)) {
            return new JsonResponse(['code' => 404, 'message' => 'Os not found'], Response::HTTP_NOT_FOUND);
        }

        $hateoas = HateoasBuilder::create()->build();

        $data = $hateoas->serialize($osPhones, 'json');

        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/api/os/{id}", methods={"GET"})
     * @param $id
     * @return Response
     *
     * @SWG\Get(
     * summary="Get Os detail",
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
     *     description="Return os phone detail",
     *     @SWG\Schema(ref=@Model(type=OsPhone::class))
     * ),
     * @SWG\Response(
     *     response=401,
     *     description="JWT Token not found or expired",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Os not found",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Server error",
     * )
     * )
     * @SWG\Tag(name="Os")
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getOsPhone(OsPhoneRepository $osPhoneRepository, CacheInterface $cache, $id)
    {
        $osPhone = $cache->get('osphones-detail-'.$id, function (ItemInterface $item) use ($osPhoneRepository, $id){
            $item->expiresAfter($this->getParameter("cache.expiration"));

            return $osPhoneRepository->findOneBy(['id' => $id]);
        });

        if (empty($osPhone)) {
            return new JsonResponse(['code' => 404, 'message' => 'Os not found for id = '.$id], Response::HTTP_NOT_FOUND);
        }

        $hateoas = HateoasBuilder::create()->build();

        $data = $hateoas->serialize($osPhone, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
