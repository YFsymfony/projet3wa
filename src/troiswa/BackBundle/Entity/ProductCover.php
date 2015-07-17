<?php

namespace troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductCover
 *
 * @ORM\Table(name="product_cover")
 * @ORM\Entity(repositoryClass="troiswa\BackBundle\Entity\ProductCoverRepository")
 *
 *
 * Doc : http://doctrine-orm.readthedocs.org/en/latest/reference/events.html
 * Doc : https://openclassrooms.com/courses/developpez-votre-site-web-avec-le-framework-symfony2/les-evenements-et-extensions-doctrine
 * @ORM\HasLifecycleCallbacks
 */
class ProductCover
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\Length(
     *      min = "2",
     *      max = "255",
     *      minMessage = "le alt doit être minimum de {{ limit }} caractères",
     *      maxMessage = "le alt doit être maximum de {{ limit }} caractères"
     * )
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;


    /**
     * Propriété pour l'upload d'image , elle est non relié à doctrine et a la base de donnée
     *
     * doc : http://symfony.com/fr/doc/current/reference/constraints.html
     *
     *       http://php.net/manual/fr/function.image-type-to-mime-type.php
     *
     *                       /!\ ATTENTION /!\
     *  Pour que les assert du formulaire ProductCover marche, on doit rajouté
     *  un assert\Valid dans l'entité parent ( ici product à la propriété cover )
     *
     * doc : http://symfony.com/fr/doc/current/reference/constraints/Valid.html
     *
     * @Assert\NotBlank(message=" L'image est obligatoire ")
     * @Assert\Image(mimeTypes={
     *                           "image/jpg",
     *                           "image/png",
     *                           "image/jpeg"
     *                         },
     *               mimeTypesMessage=" Format d'image non valide , utilisez jpg/jpeg/png "
     *               )
     *
     *       exemple erreur de typo en annotation :
     *
     *  InvalidOptionsException in Constraint.php line 161:
     *  The options "mimeType" do not exist in constraint
     *  Symfony\Component\Validator\Constraints\Image
     *
     */
    private $fileCache;

    /**
     * Propriété pour l'upload d'image , elle est non relié à doctrine
     * @var
     */
    private $oldFileCache;

    /**
     * @var array
     * tableau qui contient les différents format des images cover
     */
    private $coverSize=
        [
            "thumb-small"=>[100,100],
            "thumb-medium"=>[250,250]
        ];


    /**
     * On doit écrire le getter soit meme car cette propriété n'est pas lié à doctrine
     * la commande generate:entities ne marchera pas !
     */
    public function getFileCache()
    {
        return $this->fileCache;
    }

    /**
     * On doit écrire le setter soit meme car cette propriété n'est pas lié à doctrine
     * la commande generate:entities ne marchera pas !
     */
    public function setFileCache(UploadedFile $fileCache=null)
    {
        $this->fileCache = $fileCache;

        // Test si j'ai déjà une image
        if($this->name != null)
        {
            // J'ajoute dans oldFichier l'ancienne image
            $this->oldFileCache = $this->name;

            // je change le name pour que doctrine puisse voir que l'objet image à changer pour
            // lancer preUpdate et PostUpdate quand on modifie uniquement l'image l'orsqu'on édite un produit
            // ceci est due a l'utilisation de la propriété $fileCache qui est utilisé par le formulaire
            // est qu'elle n'est pas relié a doctrine et la base de donnée.
            $this->name = null;
        }

        return $this;
    }




    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set alt
     *
     * @param string $alt
     * @return ProductCover
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string 
     */
    public function getAlt()
    {
        return $this->alt;
    }



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * Permet d'inserer un nom d'image au moment de la sauvegarde en BDD , sinon la requete
     * donnera une erreur car elle a besoin du nom
     */
    public function preUpload()
    {
        // Création d'un nom unique d'image
        $this->name = uniqid().'.'.$this->fileCache->guessExtension();

    }



    /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     */
    public  function upload()
    {
        //die("go pour upload");

        // test pour voir si on à bien envoyer une image
        if(null == $this->fileCache)
        {
            return;
        }

        // test pour supprimer les images associé à un produit si on change les images
        // l'orsqu'on édite un produit en lui attribuant une nouvelle image
        if($this->oldFileCache)
        {
            //////////suppression des images//////////////

                //suppression de l'image principale
            unlink($this->getUploadRootDir().'/'.$this->oldFileCache);

                //suppression des thumbnails
            foreach( $this->coverSize as $key =>$size)
            {
                unlink($this->getUploadRootDir().'/'.$key.'-'.$this->oldFileCache);
            }


        }

        //dump($this->name);die;

        /*
            plus besoin de ces ligne car le nom est défini maintenant dans la fonction preUpload

        // on stock l'extension du fichier dans une variable
        $extension = $this->fileCache->guessExtension();

        // on créer un nom a l'aide d'un id unique et de l'extension du fichier
        $nameImage = uniqid().'.'.$extension;

        */

        // move contient 2 parametres , le premier est le chemin vers le dossier ou l'on veux uploader
        // et le  deuxième  le nom de l'image
        $this->fileCache->move
        (
            $this->getUploadRootDir(),
            //$nameImage    on utilise plus $nameimage car le nom est défini dans la fonction preUpload
            // on l'appel donc avec $this->name
            $this->name
        );

        /////////////////////////// Partie library IMAGINE //////////////////////////
        // DOC : http://imagine.readthedocs.org/en/latest/

        //$this->name = $nameImage; plus besoin de ce nom car on l'a dans la fonction preUpload

        $imagine = new \Imagine\Gd\Imagine();

        foreach( $this->coverSize as $key =>$size )
        {
            $imagine
                ->open($this->getAbsolutePath())
                ->thumbnail(new \Imagine\Image\Box($size[0],$size[1]))
                ->save(
                    //$this->getUploadRootDir() .'/'. $key.'-'. $nameImage);   on change cette ligne car $nameUImage n'existe plus
                    $this->getUploadRootDir() .'/'. $key.'-'. $this->name);
        }
        ////////////////////////////////////////////////////////////////////////////

    }



    /**
     * fonction permettant de retourner le chemin absolue vers les image cover.
     * Différent de getWebPath car cette fonction est utilisée dans twig
     * en effet Assets dans twig pointe directement le dossier WEB.
     */
    public function getAbsolutePath()
    {
        return $this->getUploadRootDir().'/'.$this->name;
    }


    /**
     * fonction qui sert à récupérer le chemin vers une image, utiliser dans twig
     * en combinaison de Assets qui permet de poniter directement vers le dossier WEB
     */
    public function getWebPath($size = null)
    {


        // MEMO : parcours du tableau
        //dump($this->coverSize, $this->coverSize['thumb-small'], $this->coverSize['thumb-small'][0]),die;


        //Si $size est une clé du tableau $this->coverSize alors
        // j'affiche l'image associée

        if( $size == null)
        {
            return $this->getUploadDir() . "/" . $this->name;

        }elseif( $size == "thumb-small")
                {

                    return $this->getUploadDir()."/".$size."-".$this->name;

                }elseif($size == "thumb-medium")
                        {
                            return $this->getUploadDir()."/".$size."-".$this->name;
                        }
    }

    /**
     * @return string
     * fonction qui retourne le chemin vers le dossier Products depuis le fichier entité
     * le dossier products contien les images cover
     */
    private function getUploadRootDir()
    {
        //return __DIR__."/../../../../web/Upload/Products";

        return __DIR__."/../../../../web/".$this->getUploadDir();
    }

    /**
     *
     */
    private function getUploadDir()
    {
        return "Upload/Products";
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ProductCover
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
