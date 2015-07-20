<?php

namespace troiswa\BackBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{

    //Affiche le nombre de catégorie
    public function countCategory()
    {
        $query = $this->createQueryBuilder('cat')
            ->select('COUNT(cat)');

        return $query->getQuery()->getSingleScalarResult();


    }

    // Afficher les catégories dont la position est supérieur à 2 ou $position
    public function displayCategoryByPostion($position)
    {
        $query = $this->createQueryBuilder("cat")
            ->where("cat.position > :posValue")
            ->setParameter("posValue",$position);

        return $query->getQuery()->getResult();
    }

    // afficher les catégorie don la description commence par "Produits"
    public function findCategoryStartByProduit()
    {
        $query = $this->createQueryBuilder("cat")
            ->where("cat.description LIKE 'Produits%' ");

        return $query->getQuery()->getResult();
    }

    public function findAllCategory()
    {
        $query = $this->createQueryBuilder("cat");

        return $query->getQuery()->getResult();
    }


    // cette requette est utilisable uniquement dans un form type, ici CategoryType
    // on doit utiliser uniquement un createQyeryBuilder et retourner uniquement $query !
    // si on veux utiliser cette requette autre par que dans un FormType, on peu passer un paramettre
    // dans la requette , exemple form = true ou false , et tester avec un if sur le return
    // pour choir quoi retourner en fonction de l'endroit ou l'on appel la requette.
    public function findAllCategoryOrderByPosition()
    {

        $query = $this->createQueryBuilder("cat")
            ->orderBy("cat.position","asc");

        return $query;

    }

    public function findAllProductInCategory()
    {

        $query = $this->getEntityManager()
            ->createQuery
             // je selectionne toutes les categories et tous les produits lié aux categories
             // dans l'entité category
             // jointure avec la table product la proprieté private $products de l'entitée category.
            ("

                           SELECT cat,prod
                           FROM troiswaBackBundle:Category cat
                           LEFT JOIN cat.products prod
                        ");



        //dump($query->getSQL());
        //die();

        return $query->getResult();
    }

    public function displayCategoryWhereProductsBrandIsApple(){

        $query = $this->getEntityManager()
            ->createQuery("

                SELECT cat,prod,brand
                FROM troiswaBackBundle:Category cat
                LEFT JOIN cat.products prod
                LEFT JOIN prod.brand brand
                WHERE brand.title LIKE '%Apple%'
            ");

        //dump($query->getResult());die;

        return $query->getResult();
    }

    public function countProductInCategory($catTitle)
    {

         //SELECT COUNT(product.id)
         //FROM Product
         //LEFT JOIN category cat ON cat.id = product.id_category
         //WHERE cat.title = "Produits apple"

        $query = $this->getEntityManager($catTitle)
                      ->createQuery
                      ("
                            SELECT COUNT(prod.id)
                            FROM troiswaBackBundle:Product prod
                            LEFT JOIN  prod.categ cat
                            WHERE cat.title = :title
                      ")
                      ->setParameter('title',$catTitle);

        //dump($query->getSingleScalarResult());die();

        return $query->getSingleScalarResult();
    }
}