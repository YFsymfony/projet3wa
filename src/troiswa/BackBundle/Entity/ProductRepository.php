<?php

namespace troiswa\BackBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository
{
    public function findAllMaison()
    {
    // toujours aliasser , car si on check le createQueryBuilder (ctrl click EntityRepository)
    // à la ligne 81 , on vois bien que la fonction décrite utilise un alias
        $query = $this->getEntityManager()
                      ->createQuery
                        ("
                           SELECT prod
                           FROM troiswaBackBundle:Product prod
                        ");

        return $query->getResult();
    }

    public function findProductByQuantity($quantity = null)
    {

        // ctrl+click sur createQueryBuilder pour vérifier comment cette fonction marche
        // avec createQueryBuilder() on a pas besoin d'appeler le service doctrine/manager
        $query = $this->createQueryBuilder("prod"); // équivaut a findAll FROM entity product
        if($quantity !=null)
        {
            $query->where("prod.quantity>= :qtyValue")
                  ->setParameter("qtyValue",$quantity);
        }

        return $query->getQuery()->getResult();

        //dump($query);
        //die();

        //dump($this->_entityName); // affiche le nom de l'entité pour vérifier si on est au bon endroit
        //die();
    }

    // Afficher les produits dont la quantité est inférieur à 5
    public function findProductToOrder($quantity = 5)
    {
        $query = $this->createQueryBuilder("prod")
                      ->where("prod.quantity<= :qtyValue")
                      ->setParameter("qtyValue",$quantity);

        return $query->getQuery()->getResult();


    }

    // Afficher le nombre de produit dont la quantité est à 0
    public function countProductOutOfStock()
    {
        $query = $this->createQueryBuilder('prod')
                      ->select('COUNT(prod)')
                      ->where("prod.quantity = :qtyValue")
                      ->setParameter("qtyValue",0);

        return $query->getQuery()->getSingleScalarResult();
    }

    // Afficher le nombre de produit actif
    public function countActiveProduct()
    {
        $query = $this->createQueryBuilder('prod')
            ->select('COUNT(prod)')
            ->where("prod.active = :activeValue")
            ->setParameter("activeValue",1);

        return $query->getQuery()->getSingleScalarResult();
    }

    // Afficher le nombre de produit total
    public function countAllProduct()
    {
        $query = $this->createQueryBuilder('prod')
            ->select('COUNT(prod)');

        return $query->getQuery()->getSingleScalarResult();
    }

    // Afficher le nombre de produit actif et inactif
    public function countActiveAndNonActiveProduct()
    {
        $query = $this->createQueryBuilder('prod')
                      ->select('Count(prod)')
                      ->groupBy('prod.active');

        //dump($query->getQuery()->getResult());
        //die();

        return $query->getQuery()->getResult();

    }

    // Afficher les produits dont le prix est compris entre 2 variables: ex: 10 et 20
    public function findProctBetweenPrice($minPrice,$maxPrice)
    {
        $query = $this->createQueryBuilder('prod')
                      ->where("prod.price > :min AND prod.price < :max")
                      ->setParameters(["min"=>$minPrice,"max"=>$maxPrice]);

        return $query->getQuery()->getResult();

    }

    public function findAllProduct()
    {
        $query = $this->createQueryBuilder('prod');

        return $query->getQuery()->getResult();
    }

    public function countProductToOrder($quantity = 5)
    {
        $query = $this->createQueryBuilder("prod")
            ->select('Count(prod)')
            ->where("prod.quantity<= :qtyValue")
            ->setParameter("qtyValue",$quantity);

        return $query->getQuery()->getSingleScalarResult();


    }

    public function findAllProductAndCategory()
    {

        $query = $this->getEntityManager()
            ->createQuery
            ("
                           SELECT prod,cat
                           FROM troiswaBackBundle:Product prod
                           LEFT JOIN prod.categ cat
                        ");

        return $query->getResult();
    }


}
