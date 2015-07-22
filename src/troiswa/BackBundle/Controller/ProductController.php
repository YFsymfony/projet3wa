<?php

namespace troiswa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use troiswa\BackBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use troiswa\BackBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;   // grisé car ce service est en annotation
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class ProductController extends Controller
{



    public function addProductAction(Request $request) // ne pas oublier l'objet request sinon on ne peu pas utiliser POST
    {
        /*

        // test pour l'acces à la page , ici la page n'est accessible que par l'utilisateur admin
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN'))
        {
            throw $this->createAccessDeniedException('Vous ne pouvez pas accéder à cette page');
        }

        */


        $product = new Product();

        //$product->setTitle("titre de l'article");


 ////////////// Formulaire entré en dur dans le controlleur pour comprendre les add ///////////////////////////////////////////////////////////////////////////
 //
 //       $formProduct=$this->createFormBuilder($product,["attr"=>["novalidate"=>"novalidate"]]) // en attribut , $product lie l'entitée product au formulaire
 //                         ->add("title","text")
 //                         ->add("description","textarea")
 //                         ->add("price","money")
 //                         ->add("active","choice",
 //                                   [
 //                                       "choices"=>
 //                                           [
 //                                               'true'=>" Produit disponible ",
 //                                               'false'=>" Produit indisponible "
 //                                           ],
 //                                       'expanded'=>true,
 //                                   ]
 //                               )
 //                         ->add("quantity","number")
 //                         ->add("envoyer","submit")
 //                         ->getForm();
 //       //dump($product);
 //
 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // appel du form type grace à $this->createForm
        $formProduct=$this->createForm(new ProductType(),$product,["attr"=>["novalidate"=>"novalidate"]]);

        // handleRequest récupère les info de $_POST , ce qu'a rentrer l'utilisateur , c'est pourquoi on a besoin de l'objet request
        $formProduct->handleRequest($request);
        if($formProduct->isValid())
        {

            //dump($product);
            //die;

            /////////////////////////////// Partie UPLOAD d'image ////////////////////////////////////

                // on récupère l'image grace au getter et on le stock dans $cover
                $cover = $product->getCover();

                if($cover->getAlt() == null )
                {
                    // on definie le alt en lui donnat le nom de l'image
                    $cover->setAlt($product->getTitle());
                }
                // appel de la methode upload que l'on a créer dans l'entité ProductCover
                // plus besoin d'appeller cette methode car on a ajouter un  Lifecycle Callbacks en annotation au dessus de la fonction
                //$cover->upload();

            //////////////////////////////////////////////////////////////////////////////////////////

            //die('uhggguiyt');

            $this->get("session")->getFlashBag()->add("success","Votre article est bien enregistré");

            $em = $this->getDoctrine()->getManager();

            // plus besoin de persister cover car on a ajouté cascade au OneToOne dans l'entitée product
            //$em->persist($cover);
            $em->persist($product);

            //modification apres le persist
            //$product->setTitle("modification");

            $em->flush();

            return $this->redirectToRoute("troiswa_back_product_add");

        }

        // ATTENTION : ne pas oublier de passer l'objet form à la vue et d'utiliser ->createView
        return $this->render('troiswaBackBundle:Product:addProduct.html.twig',["formProduct"=>$formProduct->createView()]);
    }







    public function allProductAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        // DOC des find :  http://www.doctrine-project.org/api/orm/2.2/class-Doctrine.ORM.EntityRepository.html
        $products = $em->getRepository("troiswaBackBundle:Product") // get repository est comme le from en sql , mais ici on parle en objet

                     // avec findAll() , on aurras pluieurs requetes
                     //->findAll

                     //  avec cette methode que l'on a faite dans le repository Product
                     //  on aurra plus qu'une seule requete.
                       ->findAllProductAndCategory();

        $sortable = $em->getRepository("troiswaBackBundle:Product")
                    ->findAllProductAndCategoryForSortable();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $sortable,
            $request->query->getInt('page', 1),
            3
        );

        //dump($products);
        //die();

        return $this->render('troiswaBackBundle:Product:allProduct.html.twig',["products"=>$products,'pagination' => $pagination]);
    }









    /**
    * @ParamConverter("product", options={ "mapping":{"idprod":"id"} } )
    * converti le parametre idprod de la route en id
    */
    public function productInfoAction(Product $product) // methode avec param converter
    {


        //dump($product);
        //die();

///////////////////////////////////// Ancienne methode///////////////////////////////////////////
//
//        METHODE AVEC $idprod en argument , remplacer par le param converter
//
//       $em = $this->getDoctrine()->getManager();
//
//        //on stock l'id dans $produit , id que l'on a chercher en BDD avec find($idprod)
//        // DOC fonction native doctrine (find) : http://www.doctrine-project.org/api/orm/2.2/class-Doctrine.ORM.EntityRepository.html
//        $product = $em->getRepository("troiswaBackBundle:Product") // get repository est comme le from en sql , mais ici on parle en objet
//        ->find($idprod);
//
//        //dump($product);
//        //die();
//
//       // pour comperendre ce test , on doit dump $product avec un id faux en url ( ../info/9999 par exemple )
//        // le dump retourne alors null , donc on écris une condition :
//        // si $product est null , alors effiche moi le NotFoundException.
//        // cette condition verifie si l'id demandée en url existe bien ,
//       // et renvois un message d'erreur grace a throw + creatNotFoundException
//
//        if(!$product) //!$product équivaut à $product == false ou empty($product) ou null == false ( verifie si $product est NULL )
//        {
//            throw $this->createNotFoundException("cette id n'existe pas");
//        }
//
/////////////////////////////////////////////////////////////////////////////////////////////////////

        //var_dump($product);
        //dump($product);
        //die();

        return $this->render('troiswaBackBundle:Product:productInfo.html.twig',array("product"=>$product));
    }










    /**
     * @param Request $request
     * @param Product $product
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("product", options={ "mapping":{"idprod":"id"} } )
     *
     * controle d'acces , ici seul l'administrateur à accès
     * arobase Security("has_role('ROLE_ADMIN')")
     * ne pas oublier ce use :
     * use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
     */
    public function editProductAction(Request $request, Product $product)// ne pas oublier l'objet request sinon on ne peu pas utiliser POST
    {

        $em = $this->getDoctrine()->getManager();


////////////////////////////////////Ancienne methode remplacé par param converter///////////////////////////////////////////////////////
//
//        //// DOC fonction native doctrine (find) : http://www.doctrine-project.org/api/orm/2.2/class-Doctrine.ORM.EntityRepository.html
//        $product = $em->getRepository("troiswaBackBundle:Product") // get repository est comme le from en sql , mais ici on parle en objet
//        ->find($idprod);
//
//        if(!$product) //!$product équivaut à $product == false ou empty($product) ou null == false ( verifie si $product est NULL )
//        {
//            throw $this->createNotFoundException("Impossible d'éditer un produit qui n'existe pas, id inconnue ");
//       }
//
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $formUpdateProduct=$this->createForm(new ProductType(),$product,["attr"=>["novalidate"=>"novalidate"]]); // l'hydration se fait grace au paramettre $product

        $formUpdateProduct->handleRequest($request);
        if($formUpdateProduct->isValid())
        {
            $cover = $product->getCover(); // ou $cover = $product->getCover()->upload();

            // plus besoin d'appeller cette methode car on a ajouter un  Lifecycle Callbacks en annotation au dessus de la fonction
            //$cover->upload();

            $this->get("session")->getFlashBag()->add("success","Votre article est bien enregistré");

            $em->flush();

            // si on redirige vers une route qui a besoin d'un paramettre , on doit le spécifier dans la redirection
            return $this->redirectToRoute("troiswa_back_product_edit", ["idprod" => $product->getId()]);  // avec l'ancienne methode on utilise $idprod et non $product->getId()
        }

        // ATTENTION : ne pas oublier de passer l'objet form à la vue et d'utiliser ->createView
        return $this->render('troiswaBackBundle:Product:editProduct.html.twig',["formUpdateProduct"=>$formUpdateProduct->createView()]);
    }











    /**
     * @param Request $request
     * @param $idprod
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @ParamConverter("product", options={ "mapping":{"idprod":"id"} } )
     */
    public  function deleteProductAction(Request $request, Product $product)
    {
        $em = $this->getDoctrine()->getManager();


//////////////////////////////////////Ancienne methode remplacé par param converter//////////////////////////////////////////////////////////////////////////
//
//        //// DOC fonction native doctrine (find) : http://www.doctrine-project.org/api/orm/2.2/class-Doctrine.ORM.EntityRepository.html
//        $product = $em->getRepository("troiswaBackBundle:Product") // get repository est comme le from en sql , mais ici on parle en objet
//        ->find($idprod);
//
//        if(!$product) //!$product équivaut à $product == false ou empty($product) ou null == false ( verifie si $product est NULL )
//        {
//            throw $this->createNotFoundException("Impossible de supprimer un produit qui n'existe pas, id inconnue ");
//        }
//
//
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        $em->remove($product);

        $em->flush();

        $this->get("session")->getFlashBag()->add("success","Votre article à bien été supprimé");

        return $this->redirectToRoute("troiswa_back_product");
    }














    public function productActiveAction(Request $request)
    {
        // utilisation de la fonction php intval() pour parser , si string la fonction zero
        $limit = intval($request->query->get("limit"));

        // si limùit  égal à , null affichera tous les résultats
        if ($limit == 0)
        {
            $limit = null;
        }

        $em = $this->getDoctrine()->getManager();

        // DOC fonction native doctrine (find) : http://www.doctrine-project.org/api/orm/2.2/class-Doctrine.ORM.EntityRepository.html
        $products = $em->getRepository('troiswaBackBundle:Product')
                       ->findBy(["active"=>true],["price"=>"ASC"],$limit);

        //dump($products);
        //die();

        return $this->render('troiswaBackBundle:Product:productActive.html.twig',["products"=>$products]);
    }














    public function changeActiveProductAction(Request $request,$idprod,$changeAction)
    {
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('troiswaBackBundle:Product')
                     ->find($idprod);

        // utilisation du getter de l'entité product pour la colonne active (/!\ $product est un objet !!!! )
        $changeAction = $product->getActive();

        if( $changeAction == 1)
        {
            // j'utilise le setter ( $product est un objet !!!! )
            $product->setActive(false);
        }else{
            // j'utilise le setter ( $product est un objet !!!! )
            $product->setActive(true);
             }

        $em->flush($product);

        return $this->redirectToRoute('troiswa_back_product',["idprod"=>$idprod,"changeAction"=>$changeAction]);
    }

















    ///////////////////////////////// methode d'entrainement ///////////////////////////////////
    public function allProductTrainAction()
    {
        $products = [
            [
                "id" => 1,
                "title" => "Mon premier produit",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now')
            ],
            [
                "id" => 2,
                "title" => "Mon deuxième produit",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now')
            ],
            [
                "id" => 3,
                "title" => "Mon troisième produit",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now')
            ],
            [
                "id" => 4,
                "title" => "",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now')
            ],
        ];

        return $this->render('troiswaBackBundle:Product:allProductTrain.html.twig',array("products"=>$products));
    }

    public function productInfoTrainAction($idprod)
    {
        $products = [
            1 => [
                "id" => 1,
                "title" => "Mon premier produit",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now'),
                "prix" => 10
            ],
            2 => [
                "id" => 2,
                "title" => "Mon deuxième produit",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now'),
                "prix" => 20
            ],
            3 => [
                "id" => 3,
                "title" => "Mon troisième produit",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now'),
                "prix" => 30
            ],
            4 => [
                "id" => 4,
                "title" => "",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('now'),
                "prix" => 410
            ],
        ];

        // cette condition verifie si l'id demandée en url existe bien ,
        // et renvois un message d'erreur grace a throw + creatNotFoundException
        if(!isset($products[$idprod]))
        {
            throw $this->createNotFoundException("cette id n'existe pas");
        }

        $product = $products[$idprod];

        return $this->render('troiswaBackBundle:Product:productInfoTrain.html.twig',array("product"=>$product));
    }




}