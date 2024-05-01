<?php

namespace App\EventListener;

use App\Exception\Core\PublishedMessageException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class PublishedMessageExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof PublishedMessageException) {
            return;
        }


        $responseData = [
            'error' => [
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $exception->getMessage()
            ]
        ];

        $event->setResponse(new JsonResponse($responseData, Response::HTTP_BAD_REQUEST));
    }
}