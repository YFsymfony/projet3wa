<?php

namespace troiswa\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use troiswa\FrontBundle\Entity\CommentRepository;


class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // On doit récupérer l'id du produit pour faire fonctionner
        // le query builder dans ce form type, l'objet qui contien
        // cette id est dans $builder
        $comment = $builder->getData();
        $idProduct = $comment->getProduct()->getId();

        $builder
            ->add('note','number')
            ->add('content','textarea')
            ->add('parent',"entity",
                [
                    "class"=>"troiswaFrontBundle:Comment",
                    "property" => "id",
                    // on peu récupérer l'id du produit dans l'objet product car on a lié
                    // l'objet product a l'objet comment grace a la ligne :
                    // $comment->setProduct($product);
                    "query_builder" => function(CommentRepository $er) use($idProduct)
                    {

                        //dump($idProduct);die;

                        // Undefined method 'findAllCommentOfOneProduct'.
                        // The method name must start with either findBy or findOneBy!
                        //  si on retourne le résultat de la requette plutot que la requette elle meme
                        // dans le repository
                        return $er->findAllCommentOfOneProduct($idProduct);
                    },

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
            'data_class' => 'troiswa\FrontBundle\Entity\Comment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'troiswa_frontbundle_comment';
    }
}