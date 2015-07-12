<?php

namespace troiswa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use troiswa\BackBundle\Form\ContactType;

//use Symfony\Component\HttpFoundation\Response;


class MainController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $productsByQuantity = $em->getRepository("troiswaBackBundle:Product")
                               ->findProductByQuantity(100);

        $productToOrder = $em->getRepository("troiswaBackBundle:Product")
                             ->findProductToOrder();

        $countProductOutOfStock = $em->getRepository("troiswaBackBundle:Product")
                                     ->countProductOutOfStock();

        $countCategory = $em->getRepository("troiswaBackBundle:Category")
                            ->countCategory();

        $countActiveProduct = $em->getRepository("troiswaBackBundle:Product")
                                 ->countActiveProduct();

        $countAllProduct = $em->getRepository("troiswaBackBundle:Product")
                              ->countAllProduct();

        $countActiveAndNonActiveProduct = $em->getRepository("troiswaBackBundle:Product")
                                             ->countActiveAndNonActiveProduct();

        $displayCategoryByPostion = $em->getRepository("troiswaBackBundle:Category")
                                       ->displayCategoryByPostion(2);

        $ProctBetweenPrice = $em->getRepository("troiswaBackBundle:Product")
                                ->findProctBetweenPrice(400,900);

        $allproducts = $em->getRepository("troiswaBackBundle:Product")
                          ->findAllProduct();

        $allCategory = $em->getRepository("troiswaBackBundle:Category")
            ->findAllCategory();

        $countProductToOrder = $em->getRepository("troiswaBackBundle:Product")
            ->countProductToOrder();




        return $this->render
        ('troiswaBackBundle:Main:index.html.twig',

            [
                "productsByQuantity"=>$productsByQuantity,
                "productToOrder"=>$productToOrder,
                "countProductOutOfStock"=>$countProductOutOfStock,
                "countCategory"=>$countCategory,
                "countActiveProduct"=>$countActiveProduct,
                "countAllProduct"=>$countAllProduct,
                "countActiveAndNonActiveProduct"=>$countActiveAndNonActiveProduct,
                "displayCategoryByPostion"=>$displayCategoryByPostion,
                "ProctBetweenPrice"=>$ProctBetweenPrice,
                "allproducts"=>$allproducts,
                "allCategory"=>$allCategory,
                "countProductToOrder"=>$countProductToOrder

            ]
        );
    }



    public function contactAction(Request $request)  //$request est une variable native de symfony qui contien les superglobale de php get/post/session/cookie
    {
        // appel du formulaire ContactType du dossier Form , le parametre null signifie que le formulaire est lié a aucune entité
        // l'attribu["attr"=>["novalidate"=>"novalidate"]] permet de désactivé l'attribut HTML require des input en mettant novalidate sur la balise form
        $formcontact =$this->createForm(new ContactType(),null,["attr"=>["novalidate"=>"novalidate"]]);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//        //traitement du formulaire version déprécié
//
//        if($request->isMethod("POST")) // vérification si la methode est bien en post ( le premier affichage de la page est en get)
//        {
//            $formcontact->submit($request); // hydratation du formulaire
//
//            if($formcontact->isValid())
//            {
//                $data = $formcontact->getData(); // récupère les info du formulaire en methode post
//                dump($data);
//                die();
//            }
//        }
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //traitement du formulaire version non déprécié


        // remplace le if de isMethod et submit($request)
        $formcontact->handleRequest($request);
            if($formcontact->isValid())
            {
                // récupère les info du formulaire en methode post
                $data = $formcontact->getData();
                //dump($data);
                //die();

                //dump($this->renderView("troiswaBackBundle:Mails:email.html.twig"));
                //die();


                // utilisation du service swift mailler
                $message = \Swift_Message::newInstance()
                    ->setSubject($data["subject"])
                    ->setFrom($data["email"])
                    ->setTo('faucher.yoann@gmail.com')
                    //->setBody("du contenu");
                    ->setBody($this->renderView('troiswaBackBundle:Mails:email.html.twig', ['data' => $data]), 'text/html')
                    ->addPart($this->renderView('troiswaBackBundle:Mails:contact-email.txt.twig', []), 'text/plain');

                // apell du service mailer
                $this->get('mailer')->send($message);

                $this->get("session")->getFlashBag()->add("success","Votre message a bien été envoyer");

                // Methode du POST redirect GET pour ne pas afficher le pop up de réenvois du formulaire

                //ANCIENNE METHOD DEPRESSIEE
                   //return $this->redirect($this->generateUrl("troiswa_back_contact"));

                // nouvelle methode
                return $this->redirectToRoute("troiswa_back_contact");
            }


        return $this->render('troiswaBackBundle:Main:contact.html.twig',["formcontact"=>$formcontact->createView()]);
    }

    public function feedbackAction(Request $request)
    {
        // formulaire en dure dans le controller pour s'entrainer
        // lien doc contraintes : http://symfony.com/fr/doc/current/reference/constraints.html
        $formfeedback = $this->createFormBuilder()
                             ->add("firstname","text",
                                        [
                                            'constraints'=>
                                                [
                                                    new Assert\NotBlank(),
                                                    new Assert\Length
                                                    (
                                                        [
                                                            'min'=>2,
                                                            'minMessage'=>"Votre prénom doit comporter au moin 2 caractères minimum"
                                                        ]
                                                    )
                                                ]
                                        ]
                                  )
                             ->add("lastname","text",
                                        [
                                            'constraints'=>
                                                [
                                                    new Assert\NotBlank(),
                                                    new Assert\Length
                                                    (
                                                        [
                                                            'min'=>2,
                                                            'minMessage'=>"Votre nom doit comporter au moin 2 caractères minimum"
                                                        ]
                                                    )
                                                ]
                                        ]
                                  )
                             ->add("email","text",
                                        [
                                            'constraints'=>
                                                [
                                                    new Assert\NotBlank(),
                                                    new Assert\Email
                                                        ([
                                                            'message'=>" '{{ value }}' n'est pas un email valide",
                                                            'checkMX'=> false,
                                                        ])
                                                ]
                                        ]
                                  )
                             ->add("url","text",
                                        [
                                            'constraints'=>
                                                [
                                                    new Assert\Url
                                                    (
                                                        [
                                                            'message'=>"Vous devez rentrer une url valide , copier la directement de votre navigateur"
                                                        ]
                                                    )
                                                ]
                                        ]
                                  )
                             ->add("statut","choice",
                                        [
                                            "choices"=>
                                                [
                                                    'affichage'=>"Bug d'affichage",
                                                    'fonctionnel'=>"Bug fonctionnel",
                                                    '404'=>"Erreur 404"
                                                ],
                                            'expanded'=>true,
                                            "constraints" =>
                                                [
                                                    new Assert\Choice
                                                    (
                                                        [
                                                        "choices" => ["affichage", "fonctionnel", "404"],
                                                        'message' => 'Choisissez une option parmis celle proposées.'
                                                        ]
                                                    )
                                                ]
                                        ]
                                  )
                             ->add("description","textarea",
                                        [
                                            "constraints"=>
                                                [
                                                    new Assert\NotBlank(),
                                                    new Assert\Length
                                                    (
                                                        [
                                                            "min"=>10,
                                                            "max"=>400,
                                                            "minMessage"=>"Veuillez entrer un minimum de 10 caractères",
                                                            "maxMessage"=>"Veuillez entrer un maximum de 400 caractères"
                                                        ]
                                                    )
                                                ]
                                        ]
                                  )
                             ->add("date","datetime",
                                        [
                                            "constraints"=>
                                                [
                                                    new Assert\DateTime()
                                                ],

                                            "widget"=>"single_text" // il faut changer le type du widget pour que le datepicker fonctionne ( si on regarde le input du date picker , le type est de text et non datepicker )
                                        ]
                                  )
                             ->add("envoyer","submit")
                             ->getForm();


        // hydratation du form , récupère les infos qu'a rentré l'utilisateur grace a $_POST
        // contenu dans l'objet request
        $formfeedback->handleRequest($request);

        // test de validation du formulaire
        if($formfeedback->isValid())
        {
            //récupère les info stocké dans $formfeedback
            $data = $formfeedback->getData();



            // Enregistrement dans les logs
            $logger = $this->get('logger');

            // Si le status est affichage alors
                // $logger->info();
            if ($data["statut"] == "affichage")
            {
                $logger->info('Erreur d\'affichage sur la page '.$data["url"].' signalé par l\'utilisateur prénom:'.$data["firstname"].' nom: '.$data["lastname"].' le '.$data["date"]->format('Y-m-d h:i:s') );
            };

            // Si le status est fonctionnel alors
             // $logger->error();
            if ($data["statut"] == "fonctionnel")
            {
                $logger->error('Erreur fonctionnel sur la page '.$data["url"].' signalé par l\'utilisateur prénom:'.$data["firstname"].' nom: '.$data["lastname"].' le '.$data["date"]->format('Y-m-d h:i:s') );
            };



            // utilisation du service swiftmailer
            $message = \Swift_Message::newInstance()
                ->setSubject($data["statut"])
                ->setFrom($data["email"])
                ->setTo('faucher.yoann@gmail.com')
                ->setBody($this->renderView('troiswaBackBundle:Mails:mail-feedback.html.twig', ['data' => $data]), 'text/html')
                ->addPart($this->renderView('troiswaBackBundle:Mails:mail-feedback-text.txt.twig', []), 'text/plain');

            $this->get('mailer')->send($message);
            $this->get("session")->getFlashBag()->add("success","Votre message a bien été envoyer");


            return $this->redirectToRoute("troiswa_back_feedback");

        }

        return $this->render("troiswaBackBundle:Main:feedback.html.twig",["formfeedback"=>$formfeedback->createView()]);
    }

    public function dqlTrainAction()
    {
      //  $em = $this->getDoctrine()->getManager();

        //Doctrine permet également d'écrire des requêtes plus complexes en utilisant
        // le Doctrine Query Language (DQL). Le DQL est très ressemblant au SQL excepté
        // que vous devez imaginer que vous requêtez un ou plusieurs objets d'une classe
        // d'entité (ex: Product) au lieu de requêter des lignes dans une table (ex: product).



////////////////////////////////////////////////////////////////////////////////////////////////////
//
//       // Attention pour que la requette fonctionne , on est obligé d'aliasser car on est
//        // avec des objets et non des lignes dans une BDD
//
//        $query = $em->createQuery(
//
//            "SELECT prod
//             FROM troiswaBackBundle:Product prod
//             WHERE prod.quantity > :quantityproduit")
//           ->setParameter("quantityproduit",100);
//
//        $product = $query->getResult();
//
//        //La méthode getSingleResult() lève une exception Doctrine\ORM\NoResultException
//        // si aucun résultat n'est retourné et une exception Doctrine\ORM\NonUniqueResultException
//        // si plus d'un résultat est retourné. Si vous utilisez cette méthode, vous devrez sans
//        // doute l'entourer d'un bloc try/catch pour vous assurer que seul un résultat est retourné
//        // (si vous requêtez quelque chose qui pourrait retourner plus d'un résultat):
//
//
//
//        $query = $em->createQuery('SELECT ...')
//        ->setMaxResults(1);
//
//        try {
//        $product = $query->getSingleResult();
//        } catch (\Doctrine\Orm\NoResultException $e) {
//            $product = null;
//       }
//        // ...
//
/////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////
//     $query = $em->createQueryBuilder()
//                  ->select("prod")         //ou "prod.title"
//                  ->from(" troiswaBackBundle:Product " , "prod" )    // "prod" est l'alias
//                  ->getQuery();
//
//
//        $product=$query->getResult();
//
//
//        dump($product);
//        die();
//
//        return $this->render('troiswaBackBundle:Main:dqlTrain.html.twig');
//
////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////UTILISATION DE REQUETE SITUEE DANS UN REPOSITORY//////////////////////////

         $em = $this->getDoctrine()->getManager();

        $productRepo = $em->getRepository("troiswaBackBundle:Product") // équivaut au FROM
                          ->findAllMaison();                           // requète maison dans le repository Product

        //dump($productRepo);
        //die();

    }
}