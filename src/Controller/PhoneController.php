<?php

namespace App\Controller;

use App\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class PhoneController extends AbstractController
{
    /**
     * @Rest\Get("api/phone/list")
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
     * @Rest\Get("api/phone/detail/{id}")
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
