<?php
namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;

class AuthListener
{
    private $tokens;
    
    public function __construct($tokens)
    {
        $this->tokens = $tokens;
    }
    
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        if (!is_array($controller)) {
            return;
        }
        if (!in_array($event->getRequest()->headers->get("token"), $this->tokens)) {
            $event->setController(function() {
                return new JsonResponse(array('error'=>'This action expects a valid token on the header of the request'));
            });
        }
    }
}