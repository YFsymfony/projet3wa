<?php

namespace troiswa\BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class ContactType extends AbstractType
{
    // lien doc contraintes : http://symfony.com/fr/doc/current/reference/constraints.html
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("firstname","text",
            [
                'constraints'=>
                    [
                        new Assert\NotBlank
                        (
                            [
                                'message'=>'Champ obligatoire!'
                            ]
                        ),
                        new Assert\Length(
                            [
                                'min'=>2,
                                'minMessage'=>'Le prénom doit etre d\'un minimum de 2 caractères'
                            ]
                        )
                    ]
            ]
        )

            ->add("lastname","text",
                [
                    'constraints'=>
                        [
                            new Assert\NotBlank
                            (
                                [
                                    'message'=>'Champ obligatoire!'
                                ]
                            ),
                            new Assert\Length
                            (
                                [
                                    'min'=>5,
                                    'minMessage'=>'Le nom doit etre d\'un minimum de 5 caractères'
                                ]
                            )
                        ]
                ]
            )
            ->add("subject","choice", // atention au s à choice et choices !
                array(
                    'choices' =>
                        array(
                            'suivis'=>'suivis de commandes',
                            'litige'=>'litige',
                            'support'=>'support technique',
                            'duplicata'=>'duplicata de facture'
                        ),
                    //   'expanded'=>true,              expended et multiple doivent etre au meme niveau que 'choices'
                    //   'multiple' =>true,             ces parametre transforme la list en check box avec choix multiple

                    // constraints doit etre au meme niveau que choices
                    "constraints" => [
                        new Assert\Choice([
                            "choices" => ["suivis", "litige", "support", "duplicata"],
                            'message' => 'Choisissez une option parmis celle proposées.'
                        ])
                    ]
                )
            )
            ->add("email","email",
                [
                    'constraints'=>
                        [
                            new Assert\NotBlank
                            (
                                [
                                    'message'=>'Champ obligatoire!'
                                ]
                            ),
                            new Assert\Email
                            (
                                [
                                    'message'=>" '{{ value }}' n'est pas un email valide",
                                    'checkMX'=> false, // verif du nom de dommaine
                                ]
                            )
                        ]
                ]
            )


            ->add("content","textarea",
                [
                    'constraints'=>
                        [
                            new Assert\NotBlank
                            (
                                [
                                    'message'=>'Champ obligatoire!'
                                ]
                            ),
                            new Assert\Length
                            (
                                [
                                    'max'=>500,
                                    'maxMessage'=>'Votre message doit contenir maximum 500 caractères'
                                ]
                            )
                        ]
                ]
            )

            ->add("date","datetime",
                [
                    "constraints"=>
                        [
                            new Assert\DateTime()
                        ],

                    "widget"=>"single_text" // il faut changer le type du widget pour que le datepicker fonctionne ( si on regarde le input du date picker , le type est de text et non datepicker )
                ]
            )

            ->add("submit","submit");
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }

    public function getName()
    {
        return 'form_contact';
    }
}