<?php

namespace troiswa\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',"text")
            ->add('description',"text")
            ->add('position',"number")
            ->add("products","entity",
                    [
                        "class"=>"troiswaBackBundle:Product",
                        //"property"=>"title",
                        // doc :http://symfony.com/doc/master/reference/forms/types/collection.html#by-reference
                        // on passe par l'entitée esclave
                        'query_builder' => function(EntityRepository $er)
                        {
                            return $er->createQueryBuilder('prod');

                        },
                        "expanded"=>false,
                        "multiple"=>true

                    ]
                 )

            ->add("envoyer","submit")
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'troiswa\BackBundle\Entity\Category'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'troiswa_backbundle_category';
    }
}
