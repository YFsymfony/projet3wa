<?php

namespace troiswa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use troiswa\BackBundle\Entity\Brand;
use troiswa\BackBundle\Form\BrandType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class BrandController extends Controller
{
    /**
     *@Security("has_role('ROLE_ADMIN')")
     */
    public function addBrandAction(Request $request)
    {
        $brand = new Brand();
        $formbrand=$this->createForm(new BrandType(),$brand,["attr"=>["novalidate"=>"novalidate"]]);

        $formbrand->handleRequest($request);

        if($formbrand->isValid())
        {
            /////////////////////////////// Partie UPLOAD d'image ////////////////////////////////////

            // on récupère le logo grace au getter et on le stock dans $logo
            $logofile = $brand->getLogofile();



            if($logofile->getAlt() == null )
            {
                // on definie le alt en lui donnat le nom du logo
                $logofile->setAlt($brand->getTitle());
            }
            // appel de la methode upload que l'on a créer dans l'entité Brand
            $logofile->upload();

            //////////////////////////////////////////////////////////////////////////////////////////

            $this->get("session")->getFlashBag()->add("success","Votre marque est bien enregistrée");

            $em = $this->getDoctrine()->getManager();

            $em->persist($brand);

            $em->flush();

            return $this->redirectToRoute("troiswa_back_brand_add");
        }

        return $this->render('troiswaBackBundle:Brand:addBrand.html.twig',["formbrand"=>$formbrand->createView()] );
    }

    /**
     * @ParamConverter("brand", options={ "mapping":{"idbrand":"id"} } )
     * @Security("has_role('ROLE_ADMIN')")
     */
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

        return $this->render('troiswaBackBundle:Brand:editBrand.html.twig',["formUpdateBrand"=>$formUpdatebrand->createView()]);
    }

    public function allBrandAction()
    {

        $em = $this->getDoctrine()->getManager();

        $brand = $em->getRepository("troiswaBackBundle:Brand")
                       ->findAllProductInBrand();

        return $this->render('troiswaBackBundle:Brand:allBrand.html.twig',["brand"=>$brand]);

    }

    /**
     * @ParamConverter("brand", options={ "mapping":{"idbrand":"id"} } )
     */
    public function brandInfoAction(Brand $brand)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('troiswaBackBundle:Brand:brandInfo.html.twig',array("brand"=>$brand));

    }

    /**
     * @Security("has_role('ROLE_SUPER_ADMIN')")
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