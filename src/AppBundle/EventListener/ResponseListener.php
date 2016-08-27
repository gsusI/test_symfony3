<?php
namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Response;

class ResponseListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if(str_replace('"','',$event->getRequest()->headers->get("If-None-Match")) === md5($event->getResponse())){
            $response = new Response();
            $response->setNotModified();
            $event->setResponse($response);
            return;
        }
        $response = $event->getResponse();
        $response->setCache(array(
            'etag'          => md5($event->getResponse()),
            'public'        => true,
        ));
        $event->setResponse($response);
    }
}