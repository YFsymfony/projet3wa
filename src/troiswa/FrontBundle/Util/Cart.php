<?php
namespace troiswa\FrontBundle\Util;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use troiswa\BackBundle\Entity\Product;

Class Cart
{
    private $session;

    private $em;

    public function __construct(Session $session,EntityManager $em)
    {
        $this->session = $session;

        $this->em = $em;
    }

    public  function  add(Product $product, $qty=0)
    {

        if ($qty > 0) {

            if ($this->session->get('cart'))
            {
                // le paramettre true permet de transformer $cart retourné par json en tableau
                // plutot qu'un objet
                $cart = json_decode($this->session->get('cart'), true);
            } else {
                $cart = [];
            }

            if (array_key_exists($product->getId(), $cart)) {
                $qty = $qty + $cart[$product->getId()]['quantity'];
            }

            $cart[$product->getId()] = ['quantity' => $qty];

            $this->session->set('cart', json_encode($cart));

        }

    }

    public function delete($id)
    {
        // le paramettre true permet de transformer $cart retourné par json en tableau
        // plutot qu'un objet
        $cart = json_decode($this->session->get('cart'), true);

        unset($cart[$id]);

        $this->session->set('cart', json_encode($cart));
    }

    public function plus($id)
    {
        // le paramettre true permet de transformer $cart retourné par json en tableau
        // plutot qu'un objet
        $cart = json_decode($this->session->get('cart'), true);

        $qty = $cart[$id]['quantity'] + 1;

        //dump($qty);die;

        $cart[$id] = ['quantity' => $qty];

        $this->session->set('cart', json_encode($cart));
    }

    public function minus($id)
    {
        // le paramettre true permet de transformer $cart retourné par json en tableau
        // plutot qu'un objet
        $cart = json_decode($this->session->get('cart'), true);

        $qty = $cart[$id]['quantity'] - 1;

        if($qty <= 0)
        {
            unset($cart[$id]);

            $this->session->set('cart', json_encode($cart));
        }else
        {
            $cart[$id] = ['quantity' => $qty];

            $this->session->set('cart', json_encode($cart));
        }
    }

    public function getProducts()
    {


        // le paramettre true permet de transformer $cart retourné par json en tableau
        // plutot qu'un objet
        $cart = json_decode($this->session->get('cart'), true);
        $products = [];

        if ($cart) {

            // $em dans construct remplace cette ligne ci dessous
            //$this->em->getDoctrine()->getManager();


            // recupère un tableau comme ceci [ 0=>id1 , 1=> id2 ect ect ]
            // cela suffit pour la methode findProductInCart car doctrine
            // fait explode/implode automatiquement.
            $idProducts = array_keys($cart);

            // findProductInCart demande en paramettre un tableau avec tous les id des produits
            // pour faire fonctionner le Where In de la requète.
            $products = $this->em->getRepository('troiswaBackBundle:Product')
                ->findProductInCart($idProducts);

        }

        //return ["products"=>$products,"panier"=>$cart];

        return $products;

    }

    public function getCart()
    {
        $cart = json_decode($this->session->get('cart'), true);

        return $cart;
    }

    public function deleteAll()
    {

        $cart = json_decode($this->session->get('cart'), true);

        unset($cart);

        $cart=[];

        $this->session->set('cart', json_encode($cart));

        //$this->session->remove('cart'); = unset($cart);$cart=[];

    }

}