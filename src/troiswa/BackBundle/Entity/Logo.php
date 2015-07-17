<?php

namespace troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Logo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="troiswa\BackBundle\Entity\LogoRepository")
 */
class Logo
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
     *
     * @Assert\Length(
     *      min = "2",
     *      max = "255",
     *      minMessage = "le nom de l'article doit etre minimum de {{ limit }} caractères",
     *      maxMessage = "le nom de l'article doit etre maximum de {{ limit }} caractères"
     * )
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\Length(
     *      min = "2",
     *      max = "255",
     *      minMessage = "le nom de l'article doit etre minimum de {{ limit }} caractères",
     *      maxMessage = "le nom de l'article doit etre maximum de {{ limit }} caractères"
     * )
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @var string
     *
     * @Assert\Length(
     *      min = "2",
     *      max = "255",
     *      minMessage = "le nom de l'article doit etre minimum de {{ limit }} caractères",
     *      maxMessage = "le nom de l'article doit etre maximum de {{ limit }} caractères"
     * )
     * @ORM\Column(name="figcaption", type="string", length=255)
     */
    private $figcaption;

//////////////////////////////////// Propriété OBLIGATOIRE POUR L'upload ///////////////////////

    /**
     * @Assert\NotBlank(message=" L'image est obligatoire ")
     * @Assert\Image(mimeTypes={
     *                           "image/jpg",
     *                           "image/png",
     *                           "image/jpeg"
     *                         },
     *               mimeTypesMessage=" Format d'image non valide , utilisez jpg/jpeg/png "
     *               )
     */
    private $logofile;

    /**
     * On doit écrire le getter soit meme car cette propriété n'est pas lié à doctrine
     * la commande generate:entities ne marchera pas !
     */
    public function getLogofile()
    {
        return $this->logofile;
    }

    /**
     * On doit écrire le setter soit meme car cette propriété n'est pas lié à doctrine
     * la commande generate:entities ne marchera pas !
     */
    public function setLogofile(UploadedFile $logofile=null)
    {
        $this->logofile = $logofile;

        return $this;
    }

//////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     *
     */
    public function upload()
    {
        //die("go pour upload");

        if(null == $this->logofile)
        {
            return;
        }

        $extension = $this->logofile->guessExtension();

        $namelogo = uniqid().'.'.$extension;

        $this->logofile->move
        (
            $this->getUploadRootDir(),
            $namelogo
        );

        /////////////////////////// Partie library IMAGINE //////////////////////////

        $this->name = $namelogo;

        $imagine = new \Imagine\Gd\Imagine();

        $imagine
            ->open($this->getAbsolutePath())
            ->thumbnail(new \Imagine\Image\Box(250, 250))
            ->save(
                $this->getUploadRootDir().'/logo-small-'.$namelogo);

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
     * fonction qui sert a récupérer le chemin vers une image, utiliser dans twig
     * en combinaison de Assets qui permet de poniter directement vers le dossier WEB
     */
    public function getWebPath()
    {

        return $this->getUploadDir()."/".$this->name;
    }

    /**
     * @return string
     * fonction qui retourne le chemin vers le dossier Products depuis le fichier entité
     * le dossier products contien les images cover
     */
    private function getUploadRootDir()
    {

        return __DIR__."/../../../../web/".$this->getUploadDir();
    }

    /**
     *
     */
    private function getUploadDir()
    {
        return "Upload/Marques";
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
     * Set name
     *
     * @param string $name
     * @return Logo
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

    /**
     * Set alt
     *
     * @param string $alt
     * @return Logo
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
     * Set figcaption
     *
     * @param string $figcaption
     * @return Logo
     */
    public function setFigcaption($figcaption)
    {
        $this->figcaption = $figcaption;

        return $this;
    }

    /**
     * Get figcaption
     *
     * @return string 
     */
    public function getFigcaption()
    {
        return $this->figcaption;
    }
}
