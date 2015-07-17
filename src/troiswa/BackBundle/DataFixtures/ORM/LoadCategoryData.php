<?php
namespace Troiswa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use troiswa\BackBundle\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;



    class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
    {

        //  cette fonction permet aux fixtures de se charger dans un certain ordre,
        // on charge en premier les entitées inverse et en dernier les entitées
        // propriétaire des clées étrangère
        // ne pas oublier le use.
        public function getOrder()
        {
            return 1;
        }


        public function load(ObjectManager $manager)
        {

            $listCategory = array(
                array(
                    'title' => 'Produits apple',
                    'description' => 'Produits généralement tres tres cher, qui ne servent qu\'a enrichir ,les descendants de Steeve Jobs',
                    'position' => '1',
                ),
                array(
                    'title' => 'Produits Macrosoft',
                    'description' => 'Produits assez cher qui ne fonctionne généralement pas tres bien',
                    'position' => '2',
                ),
                array(
                    'title' => 'Produits Sunny',
                    'description' => 'Produits de marque asiatique fabriqué par des enfants ninja du sud du Sri-Lanka',
                    'position' => '3',
                ),

            );


            foreach ($listCategory as $key => $categoryVal) {

                $category = new Category();
                $category->setTitle($categoryVal['title']);
                $category->setDescription($categoryVal['description']);
                $category->setPosition($categoryVal['position']);
                $manager->persist($category);
                // ici on tulise addReference avec une clé index (concaténation)
                // en premier parametre , et en second l'entité
                $this->addReference("refcat-" . $key, $category);

            }

            $manager->flush();

        }
    }

        /*
        $category = new Category();
        $category2 = new Category();
        $category3 = new Category();
        $category4 = new Category();

        $category->setTitle('Cat 1');
        $category->setDescription('lorem ipsum');
        $category->setPosition('1');


        $category2->setTitle('Cat 2');
        $category2->setDescription('lorem ipsum');
        $category2->setPosition('2');



        $category3->setTitle('Cat 3');
        $category3->setDescription('lorem ipsum');
        $category3->setPosition('3');


        $category4->setTitle('Cat 4');
        $category4->setDescription('lorem ipsum');
        $category4->setPosition('4');


        $manager->persist($category);
        $manager->flush();

        $manager->persist($category2);
        $manager->flush();

        $manager->persist($category3);
        $manager->flush();

        $manager->persist($category4);
        $manager->flush();
        */


        //php app/console doctrine:fixtures:load              load les fixtures en écrasant la base de données
        //php app/console doctrine:fixtures:load --append     load les fixtures sans écraser la base de données


