<?php

namespace troiswa\BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class StaticController extends Controller
{
    public function indexAction()
    {

        return $this->render('troiswaBackBundle:Static:index.html.twig',array("name"=>"yoann"));
    }

    public function index2Action()
    {
        return $this->render('troiswaBackBundle:Static:index2.html.twig',["firstname"=>"yoann","age"=>"32","lastname"=>"Boulaone"]);
    }

    public function trainingAction($string)
    {
        echo $string;
        return $this->render('troiswaBackBundle:Static:training.html.twig',array("string"=>"$string"));
    }

    public function testTemplatingAction()
    {
        return $this->render('troiswaBackBundle:Static:template.html.twig');
    }

    public function othertestTemplatingAction()
    {
        return $this->render('troiswaBackBundle:Static:othertemplate.html.twig');
    }


}