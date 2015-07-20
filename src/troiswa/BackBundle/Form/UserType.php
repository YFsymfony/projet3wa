<?php

namespace troiswa\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
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
            ->add('password','repeated',
                    [
                        'type' => 'password',
                        'invalid_message' => 'les motes de passe doivent correspondre',
                        'options'=>['attr'=>['class'=>'password-field']],
                        'first_options'=>['label' => 'Mot de passe'],
                        'second_options'=>['label' => 'confirmer mot de passe']
                    ]
                 )
            ->add('Envoyer','submit')

        ;
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
