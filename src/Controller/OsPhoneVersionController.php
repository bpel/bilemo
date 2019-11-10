<?php

namespace App\Controller;

use App\Entity\OsPhoneVersion;
use App\Repository\OsPhoneVersionRepository;
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

class OsPhoneVersionController extends AbstractController
{
    /**
     * @Route("/api/osversions", methods={"GET"})
     * @return Response
     *
     * @SWG\Get(
     * summary="Get Os version list",
     * description="",
     * produces={"application/json"},
     * @SWG\Response(
     *     response=200,
     *     description="Return os version list",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=OsPhoneVersion::class, groups={"full"}))
     *     )
     *   )
     * )
     *
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer",
     *     description="Number page"
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     description="Number of element per page"
     * )
     *
     * @SWG\Tag(name="Os version")
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getPhones(Request $request, OsPhoneVersionRepository $osPhoneVersionRepository, CacheInterface $cache)
    {
        $page = $request->query->get('page');
        $limit = $request->query->get('limit');

        $osPhones = $cache->get('osphonesversions-list-p'.$page.'-l'.$limit, function (ItemInterface $item) use($osPhoneVersionRepository, $page, $limit) {
            $item->expiresAfter($this->getParameter("cache.expiration"));

            return $osPhoneVersionRepository->findAllOsVersion($page, $limit);
        });

        if (empty($osPhones)) {
            return new JsonResponse(['code' => 404, 'message' => 'Os version not found'], Response::HTTP_NOT_FOUND);
        }

        $hateoas = HateoasBuilder::create()->build();

        $data = $hateoas->serialize($osPhones, 'json');

        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/api/osversions/{id}", methods={"GET"})
     * @param $id
     * @return Response
     *
     * @SWG\Get(
     * summary="Get Os version detail",
     * description="",
     * produces={"application/json"},
     * @SWG\Response(
     *     response=200,
     *     description="Return os version detail",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=OsPhoneVersion::class, groups={"full"}))
     *     )
     *   )
     * )
     * @SWG\Tag(name="Os version")
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getPhone(OsPhoneVersionRepository $osPhoneVersionRepository, CacheInterface $cache, $id)
    {
        $osPhoneVersion = $cache->get('osphoneversion-detail-'.$id, function (ItemInterface $item) use ($osPhoneVersionRepository, $id){
            $item->expiresAfter($this->getParameter("cache.expiration"));

            return $osPhoneVersionRepository->findOneBy(['id' => $id]);
        });

        if (empty($osPhoneVersion)) {
            return new JsonResponse(['code' => 404, 'message' => 'Os version not found for id = '.$id], Response::HTTP_NOT_FOUND);
        }

        $hateoas = HateoasBuilder::create()->build();

        $data = $hateoas->serialize($osPhoneVersion, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
