<?php

namespace App\Controller;

use App\Entity\Phone;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

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
     */
    public function getPhones()
    {
        $em = $this->getDoctrine()->getManager();
        $phones = $em->getRepository(Phone::class)->findAll();

        if (empty($phones)) {
            return new JsonResponse(['message' => 'no phone have been found'], Response::HTTP_NOT_FOUND);
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
     */
    public function getPhone($id)
    {
        $em = $this->getDoctrine()->getManager();
        $phone = $em->getRepository(Phone::class)->findPhoneById($id);

        if (empty($phone)) {
            return new JsonResponse(['message' => 'this phone does not exist'], Response::HTTP_NOT_FOUND);
        }

        $data = $this->get('serializer')->serialize($phone, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
