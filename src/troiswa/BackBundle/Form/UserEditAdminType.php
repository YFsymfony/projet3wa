<?php

namespace troiswa\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class UserEditAdminType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('birthday','datetime',["widget"=>"single_text"])
            ->add('telephone')
            ->add('pseudo')
            ->add('adress')
            //->add('roles', new RoleType());

            ->add('roles', 'entity',[
                'class' => 'troiswaBackBundle:Roles',
                // multiple a true car la propriété roles est en ManyToMany et doit renvoyer un tableau d'objet
                'multiple'=>true,
                'property' => 'name',
            ]);

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'troiswa\BackBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'troiswa_backbundle_user';
    }
}