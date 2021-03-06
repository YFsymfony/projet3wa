<?php
namespace Troiswa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use troiswa\BackBundle\Entity\Product;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;


    class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
    {
        //  cette fonction permet aux fixtures de se charger dans un certain ordre,
        // on charge en premier les entitées inverse et en dernier les entitées
        // propriétaire des clées étrangère
        // ne pas oublier le use.
        public function getOrder()
        {
            return 3;
        }

        public function load(ObjectManager $manager)
        {

            $listProduct = array(
                array(
                    'title' => 'I-Pomme',
                    'description' => 'Téléphone tres fragile et vachement cher pour pas grand chose',
                    'price' => '600',
                    'quantity' => '100',
                    // get référence permet de ciblé les clée étrangère a mettre
                    // en relation avec l'entité propriétaire
                    // on utilisera un for each avec les clées index
                    'category' => $this->getReference('refcat-0'),
                    'brand' => $this->getReference('refbrand-0')
                    ),
                array(
                    'title' => 'I-Peigne',
                    'description' => 'Peigne à cheuveux éléctronique permettant une coiffure aérodynamique',
                    'price' => '80',
                    'quantity' => '50',
                    'category' => $this->getReference('refcat-1'),
                    'brand' => $this->getReference('refbrand-0')
                    ),
                array(
                    'title' => 'I-Montre',
                    'description' => 'Montre connécter permettant de savoir quand les fions sont alignés avec les astres',
                    'price' => '900',
                    'quantity' => '80',
                    'category' => $this->getReference('refcat-2'),
                    'brand' => $this->getReference('refbrand-0')
                    ),
                array(
                    'title' => 'Ordinateur pc-Winloose',
                    'description' => 'PC avec micro pour sa soeur CPC-4200 à écran à double facialité tactile',
                    'price' => '2400',
                    'quantity' => '1500',
                    'category' => $this->getReference('refcat-0'),
                    'brand' => $this->getReference('refbrand-1')
                    ),
                array(
                    'title' => 'Enceinte 3x2watts',
                    'description' => 'Enceinte de qualité hi-fi chinoise à durée determiné ',
                    'price' => '400',
                    'quantity' => '200',
                    'category' => $this->getReference('refcat-2'),
                    'brand' => $this->getReference('refbrand-2')
                    )
            );



            foreach ($listProduct as $productVal) {

                $product = new Product();
                $product->setTitle($productVal['title']);
                $product->setDescription($productVal['description']);
                $product->setPrice($productVal['price']);
                $product->setQuantity($productVal['quantity']);
                $product->setCateg($productVal['category']);
                $product->setBrand($productVal['brand']);
                $manager->persist($product);
            }

            $manager->flush();
        }


    }


        //php app/console doctrine:fixtures:load              load les fixtures en écrasant la base de données
        //php app/console doctrine:fixtures:load --append     load les fixtures sans écraser la base de données

        /*
        $product = new Product();
        $product2 = new Product();
        $product3 = new Product();
        $product4 = new Product();

                $product->setTitle('mon super produit fixtures 1');
                $product->setDescription('lorem ipsum');
                $product->setPrice('10');
                $product->setQuantity('20');

                $product2->setTitle('mon super produit fixtures 2');
                $product2->setDescription('lorem ipsum');
                $product2->setPrice('10');
                $product2->setQuantity('20');

                $product3->setTitle('mon super produit fixtures 3');
                $product3->setDescription('lorem ipsum');
                $product3->setPrice('10');
                $product3->setQuantity('20');

                $product4->setTitle('mon super produit fixtures 4');
                $product4->setDescription('lorem ipsum');
                $product4->setPrice('10');
                $product4->setQuantity('20');

        $manager->persist($product);
        $manager->flush();

        $manager->persist($product2);
        $manager->flush();

        $manager->persist($product3);
        $manager->flush();

        $manager->persist($product4);
        $manager->flush();

        */


