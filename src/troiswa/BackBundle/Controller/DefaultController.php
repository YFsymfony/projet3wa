<?php

namespace troiswa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('troiswaBackBundle:Default:index.html.twig', array('name' => $name));
    }
}
