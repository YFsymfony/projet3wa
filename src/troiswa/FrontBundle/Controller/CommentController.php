<?php

namespace troiswa\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use troiswa\BackBundle\Entity\Product;
use troiswa\FrontBundle\Entity\Comment;
use troiswa\FrontBundle\Form\CommentType;


class CommentController extends Controller
{
    public function editCommentAction(Comment $comment, Product $product,Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $formUpdateComment=$this->createForm(new CommentType(),$comment,["attr"=>["novalidate"=>"novalidate"]]);

        $formUpdateComment->handleRequest($request);


        if($formUpdateComment->isValid())
        {

            $this->get("session")->getFlashBag()->add("success","Votre commentaire est bien enregistré");

            $em->flush();

            return $this->redirectToRoute("troiswa_front_product_info", ["idprod" => $product->getId()]);
        }

        return $this->render('troiswaFrontBundle:Comment:editComment.html.twig',
            [
                "formUpdateComment"=>$formUpdateComment->createView(),
                "product"=>$product,
                "comment"=>$comment
            ]);

    }

    public function deleteCommentAction()
    {



    }
}