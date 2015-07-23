<?php

namespace troiswa\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $lastTreeActiveProduct = $em->getRepository("troiswaBackBundle:Product")
            ->lastTreeActiveProduct();


        return $this->render('troiswaFrontBundle:Home:home.html.twig',
            [
                "lastTreeActiveProduct"=>$lastTreeActiveProduct,
            ]);
    }
}