<?php

namespace troiswa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use troiswa\BackBundle\Entity\Brand;
use troiswa\BackBundle\Form\BrandType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class BrandController extends Controller
{
    public function addBrandAction(Request $request)
    {
        $brand = new Brand();

        $formbrand=$this->createForm(new BrandType(),$brand,["attr"=>["novalidate"=>"novalidate"]]);

        $formbrand->handleRequest($request);

        if($formbrand->isValid())
        {
            $this->get("session")->getFlashBag()->add("success","Votre marque est bien enregistrée");

            $em = $this->getDoctrine()->getManager();

            $em->persist($brand);

            $em->flush();

            return $this->redirectToRoute("troiswa_back_brand_add");
        }

        return $this->render('troiswaBackBundle:Brand:addBrand.html.twig',["formbrand"=>$formbrand->createView()] );
    }

    public  function editBrandAction(Request $request, Brand $brand)
    {

        $em = $this->getDoctrine()->getManager();

        $formUpdatebrand=$this->createForm(new BrandType(),$brand,["attr"=>["novalidate"=>"novalidate"]]);

        $formUpdatebrand->handleRequest($request);

        if($formUpdatebrand->isValid())
        {

            $this->get("session")->getFlashBag()->add("success","Votre marque est bien enregistré");

            $em->flush();

            return $this->redirectToRoute("troiswa_back_brand_edit", ["idbrand" => $brand->getId()]);
        }

        return $this->render('troiswaBackBundle:Brand:editBrand.html.twig',["formUpdateCategory"=>$formUpdatebrand->createView()]);
    }

    public function allBrandAction()
    {

        $em = $this->getDoctrine()->getManager();

        $brand = $em->getRepository("troiswaBackBundle:Brand")
                       ->findAllProductInBrand();

        return $this->render('troiswaBackBundle:Brand:allBrand.html.twig',["brand"=>$brand]);

    }

    /**
     * @param $idbrand
     * @return \Symfony\Component\HttpFoundation\Response
     * @ParamConverter("brand", options={ "mapping":{"idbrand":"id"} } )
     */
    public function brandInfoAction(Brand $brand)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('troiswaBackBundle:Brand:brandInfo.html.twig',array("brand"=>$brand));

    }

    /**
     * @param $idbrand
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @ParamConverter("brand", options={ "mapping":{"idbrand":"id"} } )
     */
    public function deleteBrandAction(Brand $brand)
    {

        $em = $this->getDoctrine()->getManager();

        $em->remove($brand);

        $em->flush();

        $this->get("session")->getFlashBag()->add("success","Votre marque à bien été supprimé");

        return $this->redirectToRoute("troiswa_back_brand");
    }
}