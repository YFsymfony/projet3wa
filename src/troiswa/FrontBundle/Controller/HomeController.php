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

        $findSixActiveProduct = $em->getRepository("troiswaBackBundle:Product")
                                   ->findSixActiveProduct();


        return $this->render('troiswaFrontBundle:Home:home.html.twig',
            [
                "lastTreeActiveProduct"=>$lastTreeActiveProduct,
                "findSixActiveProduct"=>$findSixActiveProduct
            ]);
    }

    public function footerProductAction()
    {
        $em = $this->getDoctrine()->getManager();

        $findTwoProductWithMoreTag = $em->getRepository("troiswaBackBundle:Tag")
            ->findTwoProductWithMoreTag();

        return $this->render('troiswaFrontBundle:Globals:productFooter.html.twig',
            [
                "findTwoProductWithMoreTag"=>$findTwoProductWithMoreTag,

            ]);
    }
}