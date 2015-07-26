<?php

namespace troiswa\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use troiswa\BackBundle\Entity\Category;

class CategoryController extends Controller
{
    public function allCategoryAction()
    {
        $em = $this->getDoctrine()->getManager();

        $allCategory = $em->getRepository("troiswaBackBundle:Category")
            ->findAllCategory();

        return $this->render('troiswaFrontBundle:Category:allCategory.html.twig',
            [
                'allCategory'=>$allCategory
            ]);
    }

    /**
     * @ParamConverter("category", options={ "mapping":{"idcat":"id"} } )
     */
    public function categoryInfoAction(Category $category, Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        $sortable = $em->getRepository("troiswaBackBundle:Category")
            ->findAllProductInOneCategory($category);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $sortable,
            $request->query->getInt('page', 1),8

        );


        return $this->render('troiswaFrontBundle:Category:categoryInfo.html.twig',
            [
                "category"=>$category,
                "pagination"=>$pagination,
            ]);

    }


}
