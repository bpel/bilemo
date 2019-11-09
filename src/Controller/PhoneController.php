<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
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
     * description="",
     * produces={"application/json"},
     * @SWG\Response(
     *     response=200,
     *     description="Return phone list",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Phone::class, groups={"full"}))
     *     )
     *   )
     * )
     * @SWG\Tag(name="Phone")
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getPhones(Request $request, PhoneRepository $phoneRepository, CacheInterface $cache)
    {
        $page = $request->query->get('page');
        $limit = $request->query->get('limit');

        $phones = $cache->get('phones-list', function (ItemInterface $item) use($phoneRepository, $page, $limit) {
            $item->expiresAfter($this->getParameter("cache.expiration"));

            return $phoneRepository->findAllPhones($page, $limit);
        });

        if (empty($phones)) {
            return new JsonResponse(['code' => 404, 'message' => 'Phone not found'], Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('serializer')->serialize($phones, 'json');

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
     * description="",
     * produces={"application/json"},
     * @SWG\Response(
     *     response=200,
     *     description="Return phone detail",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Phone::class, groups={"full"}))
     *     )
     *   )
     * )
     * @SWG\Tag(name="Phone")
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getPhone(PhoneRepository $phoneRepository, CacheInterface $cache, $id)
    {
        $phone = $cache->get('phone-detail-'.$id, function (ItemInterface $item) use ($phoneRepository, $id){
            $item->expiresAfter($this->getParameter("cache.expiration"));

            return $phoneRepository->findPhoneById($id);
        });

        if (empty($phone)) {
            return new JsonResponse(['code' => 404, 'message' => 'Phone not found for id = '.$id], Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('serializer')->serialize($phone, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
