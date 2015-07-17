<?php

namespace troiswa\BackBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use troiswa\BackBundle\Entity\CategoryRepository;


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
                        // ici on veux affichée toutes les catégories afin de lié un produit
                        // a une catégorie lors de la création d'un nouveau produit mais on
                        // veux que les catégories soit classé par ordre de position

                        /*
                        'query_builder' => function(EntityRepository $er)
                        {
                            return $er->createQueryBuilder('cat')
                                ->orderBy('cat.position', 'ASC');
                        },
                        */


                        // création d'une fonction pour appeller n'importe quelle
                        // requete du repository Category
                        // ici on veux affichée toutes les catégories afin de lié un produit
                        // a une catégorie lors de la création d'un nouveau produit mais on
                        // veux que les catégories soit classé par ordre de position
                        // c'est pour cela que l'on créer une requete
                        // sinon on utilise juste : 'property' => 'title', ainsi que
                        // la methode magique __toString() dans l'entité
                        //  /!\ attention aux use /!\
                        "query_builder" => function(CategoryRepository $er)
                        {

                            return $er->findAllCategoryOrderByPosition();
                        },

                        "required"=>false

                    ]
                )

            ->add("brand","entity",
                    [
                        "class"=>"troiswaBackBundle:Brand",
                        //'property' => 'title',
                        "expanded"=>false,
                        "multiple"=>false,
                        "required" => true,
                    ]
                 )

            // INSERTION DU PRODUCT COVER TYPE DANS LE PRODUCT TYPE
            // add('nom de la propriété présente dans l'entité
            // product qui correspond au formulaire a inssérer',
            // 'instanciation de l'objet' )
            ->add("cover", new ProductCoverType())


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
