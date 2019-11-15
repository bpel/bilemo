<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getException();
        $data = [];

        if($exception instanceof \Exception){
            $data = [
                'code' => 500,
                'message' => 'Internal Server Error'
            ];
        }

        if($exception instanceof BadRequestHttpException ){
            $data = [
                'code' => '400',
                'message' => 'Bad request'
            ];
        }

        if($exception instanceof MethodNotAllowedHttpException  ){
            $data = [
                'code' => '405',
                'message' => 'Method not allowed'
            ];
        }

        if($exception instanceof NotFoundHttpException ){
            $data = [
                'code' => $exception->getStatusCode(),
                'message' => 'Resource not found'
            ];
        }

        $response = new JsonResponse($data);
        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
