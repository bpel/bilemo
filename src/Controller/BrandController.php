<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Repository\BrandRepository;
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

class BrandController extends AbstractController
{
    /**
     * @Route("/api/brands", methods={"GET"})
     * @return Response
     *
     * @SWG\Get(
     * summary="Get brand list",
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
     *     description="Return brand list",
     *     @SWG\Schema(ref=@Model(type=Brand::class))
     * ),
     * @SWG\Response(
     *     response=401,
     *     description="JWT Token not found or expired",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Brand not found",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Server error",
     * ),
     * )
     *
     * @SWG\Tag(name="Brand")
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getBrands(Request $request, BrandRepository $brandRepository, CacheInterface $cache, Pagination $pagination)
    {
        $page = $request->query->get('page');
        $limit = $request->query->get('limit');

        if(!$pagination->isValidParameters($page, $limit))
        {
            return new JsonResponse(['code' => 400, 'message' => 'Bad parameters for pagination'], Response::HTTP_BAD_REQUEST);
        }

        $brands = $cache->get('brands-list-p'.$page.'-l'.$limit, function (ItemInterface $item) use($brandRepository, $page, $limit) {
            $item->expiresAfter($this->getParameter("cache.expiration"));

            return $brandRepository->findAllBrands($page, $limit);
        });

        if (empty($brands)) {
            return new JsonResponse(['code' => 404, 'message' => 'Brand not found'], Response::HTTP_NOT_FOUND);
        }

        $hateoas = HateoasBuilder::create()->build();

        $data = $hateoas->serialize($brands, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/api/brands/{id}", methods={"GET"})
     * @param $id
     * @return Response
     *
     * @SWG\Get(
     * summary="Get brand detail",
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
     *     description="Return brand detail",
     *     @SWG\Schema(ref=@Model(type=Brand::class))
     * ),
     * @SWG\Response(
     *     response=401,
     *     description="JWT Token not found or expired",
     * ),
     * @SWG\Response(
     *     response=404,
     *     description="Brand not found",
     * ),
     * @SWG\Response(
     *     response=500,
     *     description="Server error",
     * )
     * )
     * @SWG\Tag(name="Brand")
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getBrand(BrandRepository $brandRepository, CacheInterface $cache, $id)
    {
        $brand = $cache->get('brand-detail-'.$id, function (ItemInterface $item) use ($brandRepository, $id){
            $item->expiresAfter($this->getParameter("cache.expiration"));

            return $brandRepository->findOneBy(['id' => $id]);
        });

        if (empty($brand)) {
            return new JsonResponse(['code' => 404, 'message' => 'Brand not found for id = '.$id], Response::HTTP_NOT_FOUND);
        }

        $hateoas = HateoasBuilder::create()->build();

        $data = $hateoas->serialize($brand, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
