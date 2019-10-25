<?php

namespace App\Controller;

use App\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\View;

class PhoneController extends AbstractController
{
    /**
     * @Rest\Get("/phone/list")
     */
    public function getPhones()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(Phone::class)->findAll();

        $data =  $this->get('serializer')->serialize($users, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Rest\Get("/phone/detail/{id}")
     */
    public function getPhone($id)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(Phone::class)->findBy(['id' => $id]);

        $data =  $this->get('serializer')->serialize($users, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
