<?php
namespace Troiswa\BackBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use troiswa\BackBundle\Entity\Brand;
use Doctrine\Common\DataFixtures\AbstractFixture;



class LoadBrandData extends AbstractFixture implements OrderedFixtureInterface
{
    //  cette fonction permet aux fixtures de se charger dans un certain ordre,
    // on charge en premier les entitées inverse et en dernier les entitées
    // propriétaire des clées étrangère
    // ne pas oublier le use.
    public function getOrder()
    {
        return 2;
    }


    public function load(ObjectManager $manager)
    {

        $listBrand = array(
            array(
                'title' => 'Apple',
                'description' => 'Produits généralement tres tres cher, qui ne servent qu\'a enrichir ,les descendants de Steeve Jobs',
            ),
            array(
                'title' => 'Macrosoft',
                'description' => 'Produits assez cher qui ne fonctionne généralement pas tres bien',
            ),
            array(
                'title' => 'Sunny',
                'description' => 'Produits de marque asiatique fabriqué par des enfants ninja du sud du Sri-Lanka',
            ),

        );


        foreach ($listBrand as $key => $brandVal) {

            $brand = new Brand();
            $brand->setTitle($brandVal['title']);
            $brand->setDescription($brandVal['description']);
            $manager->persist($brand);
            // ici on tulise addReference avec une clé index (concaténation)
            // en premier parametre , et en second l'entité
            $this->addReference("refbrand-" . $key, $brand);

        }

        $manager->flush();

    }
}