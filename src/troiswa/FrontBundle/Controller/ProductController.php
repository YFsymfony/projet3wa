<?php

namespace troiswa\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use troiswa\BackBundle\Entity\Product;

class ProductController extends Controller
{
    /**
     * @ParamConverter("product", options={ "mapping":{"idprod":"id"} } )
     */
    public function productInfoAction(Product $product)
    {
        $em = $this->getDoctrine()->getManager();

        $findAllTagForOneProduct = $em->getRepository("troiswaBackBundle:Tag")
            ->findAllTagForOneProduct($product);

        return $this->render('troiswaFrontBundle:Product:productInfo.html.twig',
            [
                "product"=>$product,
                "findAllTagForOneProduct"=>$findAllTagForOneProduct,
            ]);
    }

    /*

    @ParamConverter("product", options={ "mapping":{"idprod":"id"} } )

    public function addCartAction(Product $product, Request $request)
    {
        // Récupération des informations du formulaire d'ajout au panier
        $qty = $request->request->getInt('quantity');
        if ($qty > 0)
        {
            // je récupère la session en premier lieu
            $session = $request->getSession();

            // remise a zéro de la variable drapeau
            $contain = false;

            // si la session possède un cart , je décode le cart avec json_decode
            if($session->get('cart'))
            {
                // (array) permet d'avoir un tableau comme container et non un objet
                // pour pouvoir utiliser array_push
                $cart = (array)json_decode($session->get('cart'));
            }else
            {   // sinon je créer un  tableau vide
                $cart =[];

            }

            // pour chaque produit dans cart
            foreach($cart as $oneProduct)
            {
                // $cart est un tableau contenant des objet car json_encode renvois des objets

                // si dans mon cart j'ai deja un objet avec le meme id
                // j'additionne les quantitées afin de ne pas écraser les quantitées
                if($oneProduct->id_product == $product->getid())
                {
                    $oneProduct->quantity = $oneProduct->quantity + $qty;

                    // variable drapeau pour ne pas dupliquer le produit dans le cart
                    $contain = true;
                    break;
                }
            }

            // si le drapeau est faux , alors j'insère le nouveau produit dans le drapeau
            if($contain == false)
            {
                array_push($cart,['id_product'=> $product->getId(), 'quantity'=> $qty]);
            }

            $session->set('cart', json_encode($cart));

        }

        return $this->redirectToRoute(('troiswa_front_cart'));

        //dump($product);dump($request->request->get('quantity'));die;
    }
    */

    /**
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @ParamConverter("product", options={ "mapping":{"idprod":"id"} } )
     */
    public function addCartAction(Product $product, Request $request)
    {
        // Récupération des informations du formulaire d'ajout au panier
        $qty = $request->request->getInt('qty');

        if ($qty > 0)
        {
            $session = $request->getSession();
            $session->remove('cart');

            if ($session->get('cart'))
            {
                $cart = json_decode($session->get('cart'), true);
            }
            else
            {
                $cart = [];
            }

            if (array_key_exists($product->getId(), $cart))
            {
                $qty = $qty +  $cart[$product->getId()]['quantity'];
            }

            $cart[$product->getId()] = ['quantity' => $qty];


        $session->set('cart', json_encode($cart));

    }

        return $this->redirectToRoute('troiswa_front_cart');

    }



    /*
    public function cartAction(Request $request)
    {
        $session = $request->getSession();

        $cart = (array)json_decode($session->get('cart')); // LIGNE PERMETTANT DE RECUP LE PANIER

        //dump($session->get('cart'));die;

        $idproducts = [];

        foreach( $cart as $idprod)
        {
            $id = $idprod->id_product;

            array_push($idproducts,$id);
        }

        //dump($idproducts);die;

        //$idproductsToString = implode(",",$idproducts);

        //dump($idproductsToString);die;

        //dump(json_decode($session->get('cart')));
        //dump($cart);
        //die;

        $em = $this->getDoctrine()->getEntityManager();

        $productInCart = $em->getRepository("troiswaBackBundle:Product")
            ->findProductInCart($idproducts);




        return $this->render('troiswaFrontBundle:Product:cart.html.twig',
            [
                "productInCart"=>$productInCart,
            ]);
    }
    */

    public function cartAction(Request $request)
    {
        $session = $request->getSession();
        $cart = json_decode($session->get('cart'), true);
        $products = [];

        if ($cart) {
            $em = $this->getDoctrine()->getManager();
            $idProducts = array_keys($cart);
            $products = $em->getRepository('')->fonction($idProducts);
        }

        return $this->render('', ['products' => $products, 'panier' => $cart]);


}



}
