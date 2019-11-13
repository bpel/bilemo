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
     * description="",
     * produces={"application/json"},
     * @SWG\Response(
     *     response=200,
     *     description="Return brand list",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Brand::class, groups={"full"}))
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
     * description="",
     * produces={"application/json"},
     * @SWG\Response(
     *     response=200,
     *     description="Return brand detail",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Brand::class, groups={"full"}))
     *     )
     *   )
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
