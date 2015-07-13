<?php

namespace troiswa\BackBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use troiswa\BackBundle\Entity\CategoryRepository;   // non utilisé

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title","text")
            ->add("description","textarea")
            ->add("price","money")
            ->add("active","choice",
                [
                    "choices"=>
                        [
                            true=>" Produit disponible ",
                            false=>" Produit indisponible "
                        ],
                    'expanded'=>true,
                    'choice_value' => function ($currentChoiceKey) {
                        return $currentChoiceKey ? 'true' : 'false';
                    }
                ]
            )


            ->add("quantity","number")
            // http://symfony.com/fr/doc/current/reference/forms/types/entity.html
            //Afficher dans un select les catégories triées par ordre de position. Utiliser la doc du dessus
            //Vous devez utiliser le repository EntityRepository afin de charger une méthode faisant ce
            //comportement
            // ne pas oublier la methode magique __toString() dans l'entité Category
            ->add("categ","entity",
                    [
                        "class"=>"troiswaBackBundle:Category",

                          //création d'une requete dans la mauvaise couche
                          //métier mais fonctionnelle

                        'query_builder' => function(EntityRepository $er)
                        {
                            return $er->createQueryBuilder('cat')
                                ->orderBy('cat.position', 'ASC');
                        },
                        "required"=>false

                    /*
                        // création d'une fonction pour appeller n'importe quelle
                        // requete du repository Category
                        // DONNE une ERREUR : Expected argument of type "Doctrine\ORM\QueryBuilder", "array" given
                        "query_builder" => function(CategoryRepository $er)
                        {

                            return $er->findAllCategoryOrderByPosition();
                        }
                    */
                    ]
                )

            ->add("envoyer","submit")
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'troiswa\BackBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'troiswa_backbundle_product';
    }
}
