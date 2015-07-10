<?php

namespace troiswa\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
