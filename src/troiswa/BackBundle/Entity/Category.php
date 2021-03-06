<?php

namespace troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use troiswa\BackBundle\Entity\Product;    // grisée car utilisé en annotation
use Symfony\Component\Validator\Context\ExecutionContextInterface; // use du call back

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="troiswa\BackBundle\Repository\CategoryRepository")
 */
class Category
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
     * @Assert\NotBlank(message=" Champ obligatoire ")
     * @Assert\Length(
     *      min = "2",
     *      max = "100",
     *      minMessage = "le nom de la category doit etre minimum de {{ limit }} caractères",
     *      maxMessage = "le nom de la category doit etre maximum de {{ limit }} caractères"
     * )
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank(message=" Champ obligatoire ")
     * @Assert\Length(
     *      min = "20",
     *      max = "1000",
     *      minMessage = "la description de la categorie doit etre minimum de {{ limit }} caractères",
     *      maxMessage = "la description de l'a categorie doit etre maximum de {{ limit }} caractères"
     * )
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var integer
     * @Assert\NotBlank(message=" Champ obligatoire ")
     * @Assert\Type(type="float", message="La valeur {{ value }} n'est pas un type {{ type }} valide.")
     * @Assert\GreaterThanOrEqual(
     *     value = 0
     * )
     * @ORM\Column(name="position", type="smallint")
     */
    private $position;

    /**
     * Avec cette facon de déclarer le targetEntity , on doit déclarer le use de l'entité correspondante
     * @ORM\OneToMany(targetEntity="Product", mappedBy="categ")
     */
    private $products;

    /**
     * @Gedmo\Slug(fields={"title","id"}, updatable=true)
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;


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
     * Set title
     *
     * @param string $title
     * @return Category
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Category
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }


    // methode magique pour convertir un objet en chaine de charactère
    // exemple d'erreur causé si on utilise pas cette methode :
    // Catchable Fatal Error: Object of class troiswa\BackBundle\Entity\Category could not be converted to string

    // doc : http://symfony.com/fr/doc/current/reference/forms/types/entity.html
    public function __toString()
    {
        return $this->title;
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add products
     *
     * @param \troiswa\BackBundle\Entity\Product $products
     * @return Category
     */
    public function addProduct(\troiswa\BackBundle\Entity\Product $products)
    {

        $this->products[] = $products;

        $products->setCateg($this);

        //dump($products);die();    affiche qu'un seul produit car symfony boucle et modifie un par un

        return $this;
    }

    /**
     * Remove products
     *
     * @param \troiswa\BackBundle\Entity\Product $products
     */
    public function removeProduct(\troiswa\BackBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);


        // sans cette ligne , si on édité une catégorie avec des produits associé ,
        // et que l'on veux retiré un produits associé, on ne pouvais pas, la modification
        // ne se faisait pas et conservé les produits associé avant l'édition.
        $products->setCateg(null);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }


    /**
     * @Assert\True(message="categorie invalide , la catégorie à la position 0 doit avoir comme nom Promotion")
     *
     * cette methode n'a pas besoin d'etre appelée par le formulaire car le nom de la méthode commence par
     * is***** en combinaison avec le assert. ( idem pour get )
     */
    public function isCategoryValid()
    {
        if( $this->position == 0 and $this->title != "Promotion" )
        {
            return false;

        }else{

                return true;

             }

    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $message)
    {


        if ($this->getTitle() != ucfirst($this->title)){

            // pour le paramettre entre acolade , attention aux espaces ,
            // les deux valeur doivent etre identique  dans buildViolation et setParameter
            $message->buildViolation('{{ parameter }} n\'est pas valide! Le nom de la categorie doit commencer par une majuscule')
                ->atPath('title')
                ->setParameter("{{ parameter }}", $this->title)
                ->addViolation();
        }
    }

    /*
    /**
     * @Assert\True(message="Nom de catégorie invalide, le nom doit commencer par une majuscule")

         probleme avec la methode isTitle , éxécute le assert mais ne rentre pas dans un test si on en met
         un dans la methode ( exemple if le titre ne commence pas par une majuscule )

    public  function isTitle()
    {
       return false;
    }
    */

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Category
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Category
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
