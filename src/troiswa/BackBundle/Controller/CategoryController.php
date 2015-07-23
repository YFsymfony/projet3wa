<?php

namespace troiswa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use troiswa\BackBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use troiswa\BackBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;   // grisé car ce service est en annotation

class CategoryController extends Controller
{


    /**
     *@Security("has_role('ROLE_ADMIN')")
     */
    public function addCategoryAction(Request $request)
    {
        // j'instancie un nouvel objet category que je stock dans la variable $category
        $category = new category();

        //j'appel le formulaire  de la classe CategoryType que j'hydrate avec $category et je désactive
        // la validation html5 avec novalidate.
        $formCategory=$this->createForm(new CategoryType(),$category,["attr"=>["novalidate"=>"novalidate"]]);

        // dans $request se trouve $_POST : les données soumises par l'utilisateur
        // Grâce a handleRequest, j'hydrate le formulaire avec les informations de $_POST donc $request
        $formCategory->handleRequest($request);



        // je test si le formulaire et valide et j'éxécute le traitement si celui ci est valide
        if($formCategory->isValid())
        {
            // je met un message flash bag qui s'affichera dans le layout_admin
            $this->get("session")->getFlashBag()->add("success","Votre Categorie est bien enregistrée");

            //j'appel le service doctrine et entity manager
            $em = $this->getDoctrine()->getManager();

            //dump($category);
            //die();

            // j'informe doctrine de l'existance de l'objet $category afin qu'il le surveille
            $em->persist($category);

            // j'effectue le flush pour modifier la BDD
            $em->flush();

            // je redirige sur la page de liste de toute les catégories en methode GET
            // Utilisation de ce concept POST/REDIRECT/GET
            // si on redirige vers une route qui a besoin d'un paramettre , on doit le spécifier dans la redirection
            return $this->redirectToRoute("troiswa_back_category");
        }

        // render de la vue initiale au premier affichage de la page , ne pas oublier
        // ["formCategory"=>$formCategory->createView()] pour afficher le formulaire
        return $this->render('troiswaBackBundle:Category:addCategory.html.twig',["formCategory"=>$formCategory->createView()]);
    }










    public function allCategoryAction()
    {
        // j'appel le service doctrine et entity manager
        $em = $this->getDoctrine()->getManager();

        //TEST
        //$query = $em->getRepository("troiswaBackBundle:Category")
        //            ->displayCategoryWhereProductsBrandIsApple();
        // END TEST

        // j'utilise le repository comme pour faire un FROM en SQL et le findAll pour récupérer
        // toute les categories
        //  DOC fonction native doctrine (find) : http://www.doctrine-project.org/api/orm/2.2/class-Doctrine.ORM.EntityRepository.html
        $category = $em->getRepository("troiswaBackBundle:Category")
                     //avec findAll() , on aurras pluieurs requetes
                     //->findAll();

                     //  avec cette methode que l'on a faite dans le repository Product
                     //  on aurra plus qu'une seule requete.
                     ->findAllProductInCategory();


        //je rend la vue de la liste de toutes les categories en passant en paramettre
        // toutes le categories trouvée par le findAll
        return $this->render('troiswaBackBundle:Category:allCategory.html.twig',["category"=>$category]);

    }










    /**
     * @ParamConverter("category", options={ "mapping":{"idcat":"id"} } )
     */
    public function categoryInfoAction(Category $category)
    {
        // appel du service doctrine et entity manager
        $em = $this->getDoctrine()->getManager();


////////////////////////////////////Ancienne methode remplacé par param converter///////////////////////////////////////////////////////
//
//        // on stock dans $category le résultat de la recherche en BDD
//        // DOC fonction native doctrine (find) : http://www.doctrine-project.org/api/orm/2.2/class-Doctrine.ORM.EntityRepository.html
//        $category = $em->getRepository("troiswaBackBundle:Category")
//        ->find($idcat);
//
//        // pour comperendre ce test , on doit dump $product avec un id faux en url ( ../info/9999 par exemple )
//        // le dump retourne alors null , donc on écris une condition :
//        // si $product est null , alors effiche moi le NotFoundException.
//        // cette condition verifie si l'id demandée en url existe bien ,
//        // et renvois un message d'erreur grace a throw + creatNotFoundException
//
//        //!$category équivaut à $category == false ou empty($category)
//        // ou null == false ( verifie si $category est NULL )
//        if(!$category)
//        {
//            throw $this->createNotFoundException("cette id n'existe pas");
//        }
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // redirection vers la vu avec les résultats en parametres
        return $this->render('troiswaBackBundle:Category:categoryInfo.html.twig',array("category"=>$category));

    }











    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @ParamConverter("category", options={ "mapping":{"idcat":"id"} } )
     */

    public  function editCategoryAction(Request $request, Category $category)
    {
        // appel du service doctrine et entity manager
        $em = $this->getDoctrine()->getManager();

////////////////////////////////////Ancienne methode remplacé par param converter///////////////////////////////////////////////////////
//
//        // DOC fonction native doctrine (find) :
//        // http://www.doctrine-project.org/api/orm/2.2/class-Doctrine.ORM.EntityRepository.html
//        // get repository est comme le from en sql , car ici on parle en objet
//        $category = $em->getRepository("troiswaBackBundle:Category")
//        ->find($idcat);
//
//
//        // pour comperendre ce test , on doit dump $product avec un id faux en url ( ../info/9999 par exemple )
//        // le dump retourne alors null , donc on écris une condition :
//        // si $product est null , alors effiche moi le NotFoundException.
//        // cette condition verifie si l'id demandée en url existe bien ,
//        // et renvois un message d'erreur grace a throw + creatNotFoundException
//
//        //!$category équivaut à $category == false ou empty($category)
//        // ou null == false ( verifie si $category est NULL )
//        if(!$category)
//        {
//            throw $this->createNotFoundException("Impossible d'éditer une categorie qui n'existe pas, id inconnue ");
//        }
//
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        // création du formulaire en instanciant un nouvel objet de type categoryType
        // lié a l'entité catégory grace au parametre $category , l'hydration se fait grace au paramettre $category
        // l'attribut ["attr"=>["novalidate"=>"novalidate"]]) desactive la validation html5 (require)
        $formUpdateCategory=$this->createForm(new CategoryType(),$category,["attr"=>["novalidate"=>"novalidate"]]);

        // dans $request se trouve $_POST : les données soumises par l'utilisateur
        // Grâce a handleRequest, j'hydrate le formulaire avec les informations de $_POST donc $request
        $formUpdateCategory->handleRequest($request);

        // je test si le formulaire et valide et j'éxécute le traitement si celui ci est valide
        if($formUpdateCategory->isValid())
        {

            // je met un message flash bag qui s'affichera dans le layout_admin
            $this->get("session")->getFlashBag()->add("success","Votre categorie est bien enregistré");

            // flush éxécute l'insertion en base de donnée
            $em->flush();

            // je redirige sur la page de liste de toute les catégories en methode GET
            // Utilisation de ce concept POST/REDIRECT/GET
            // si on redirige vers une route qui a besoin d'un paramettre , on doit le spécifier dans la redirection
            return $this->redirectToRoute("troiswa_back_category_edit", ["idcat" => $category->getId()]); // avec l'ancienne methode on utilise $idcat et non $category->getId()
        }

        // ATTENTION : ne pas oublier de passer l'objet form à la vue et d'utiliser ->createView
        // sinon le formulaire ne s'affiche pas
        return $this->render('troiswaBackBundle:Category:editCategory.html.twig',["formUpdateCategory"=>$formUpdateCategory->createView()]);
    }





    /**
     * @Security("has_role('ROLE_SUPER_ADMIN')")
     * @ParamConverter("category", options={ "mapping":{"idcat":"id"} } )
     */
    public function deleteCategoryAction(Category $category)
    {
        //appel du service doctrine et entity manager
        $em = $this->getDoctrine()->getManager();

////////////////////////////////////Ancienne methode remplacé par param converter///////////////////////////////////////////////////////
//
//        // DOC fonction native doctrine (find) : http://www.doctrine-project.org/api/orm/2.2/class-Doctrine.ORM.EntityRepository.html
//        // get repository est comme le from en sql , car ici on parle en objet
//        $category = $em->getRepository("troiswaBackBundle:Category")
//        ->find($idcat);
//
//        //!$product équivaut à $product == false ou empty($product) ou null == false ( verifie si $product est NULL )
//        if(!$category)
//        {
//            throw $this->createNotFoundException("Impossible de supprimer une categorie qui n'existe pas, id inconnue ");
//        }
//
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        // remove delete l'objet en base de donnée
        $em->remove($category);

        // flush confirm l'action sur la BDD
        $em->flush();

        // je met un message flash bag qui s'affichera dans le layout_admin
        $this->get("session")->getFlashBag()->add("success","Votre category à bien été supprimé");

        // je redirige sur la page de liste de toute les catégories en methode GET
        // Utilisation de ce concept POST/REDIRECT/GET
        // si on redirige vers une route qui a besoin d'un paramettre , on doit le spécifier dans la redirection
        return $this->redirectToRoute("troiswa_back_category");
    }







    public function listCategoryNavAction()
    {
        // appel du service doctrine et entity manager
        $em = $this->getDoctrine()->getManager();

        // get repository est comme le from en sql , car ici on parle en objet
        // DOC fonction native doctrine (find) : http://www.doctrine-project.org/api/orm/2.2/class-Doctrine.ORM.EntityRepository.html
        $category = $em->getRepository("troiswaBackBundle:Category")
        ->findAll();

        return $this->render('troiswaBackBundle:Category:listCategoryNav.html.twig',["category"=>$category]);
    }








    ///////////////////////////methode d'entrainement////////////////////////////////
    public function allCategoryTrainAction()
    {


        $categories = [
            1 => [
                "id" => 1,
                "title" => "Homme",
                "description" => "lorem ipsum \n suite du contenu",
                "date_created" => new \DateTime('now'),
                "active" => true
            ],
            2 => [
                "id" => 2,
                "title" => "Femme",
                "description" => "<strong>lorem</strong> ipsum",
                "date_created" => new \DateTime('-10 Days'),
                "active" => true
            ],
            3 => [
                "id" => 3,
                "title" => "Enfant",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('-1 Days'),
                "active" => false
            ],
        ];
        return $this->render('troiswaBackBundle:Category:allCategoryTrain.html.twig',array("categories"=>$categories));
    }

    public function categoryInfoTrainAction($idcat)
    {
        $categories = [
            1 => [
                "id" => 1,
                "title" => "Homme",
                "description" => "lorem ipsum \n suite du contenu",
                "date_created" => new \DateTime('now'),
                "active" => true
            ],
            2 => [
                "id" => 2,
                "title" => "Femme",
                "description" => "<strong>lorem</strong> ipsum",
                "date_created" => new \DateTime('-10 Days'),
                "active" => true
            ],
            3 => [
                "id" => 3,
                "title" => "Enfant",
                "description" => "lorem ipsum",
                "date_created" => new \DateTime('-1 Days'),
                "active" => false
            ],

        ];

            // cette condition verifie si l'id demandée en url existe bien ,
            // et renvois un message d'erreur grace a throw + creatNotFoundException
            if(!isset($categories[$idcat]))
            {
                throw $this->createNotFoundException("cette id n'existe pas");
            }


            $category = $categories[$idcat];

        return $this->render('troiswaBackBundle:Category:addCategoryTrain.html.twig',array("category"=>$category));
    }

}
