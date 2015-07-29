<?php

namespace troiswa\FrontBundle\Listener;

// documentation : http://symfony.com/doc/current/components/http_kernel/introduction.html
// Rechercher KernelEvents::REQUEST
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class MaintenanceListener
{
    public  function __construct($paramMaintenance, \Twig_Environment $twig)
    {
        $this->maintenance = $paramMaintenance;
        $this->twig = $twig;
    }

    public function onMaintenance(GetResponseEvent $event)
    {
        if($this->maintenance == true)
        {
            $content = $this->twig->render('troiswaFrontBundle:Maintenance:maintenance.html.twig');
            $event->setResponse(new Response($content, 503));
            $event->stopPropagation();
        }
    }
}