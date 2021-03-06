<?php

namespace troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Context\ExecutionContextInterface; // use du call back
use troiswa\BackBundle\Validator\insulteFilter;
use troiswa\BackBundle\Entity\Product;

/**
 * Brand
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="troiswa\BackBundle\Repository\BrandRepository")
 */
class Brand
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
     *      minMessage = "le nom de l'article doit etre minimum de {{ limit }} caractères",
     *      maxMessage = "le nom de l'article doit etre maximum de {{ limit }} caractères"
     * )
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank(message=" Champ obligatoire ")
     * @Assert\Length(
     *      min = "20",
     *      max = "1000",
     *      minMessage = "la description de l'article doit etre minimum de {{ limit }} caractères",
     *      maxMessage = "la description de l'article doit etre maximum de {{ limit }} caractères"
     * )
     * @ORM\Column(name="description", type="text")
     *
     * @insulteFilter(message=" Soyez poli , pas d'insulte s'il vous plais !")
     */
    private $description;

    /**
     * @var string
     ** @Gedmo\Slug(fields={"title","id"}, updatable=true)
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * Avec cette facon de déclarer le targetEntity , on doit déclarer le use de l'entité correspondante
     * @ORM\OneToMany(targetEntity="Product", mappedBy="brand")
     */
    private $products;

    /**
     * @ORM\OneToOne(targetEntity="troiswa\BackBundle\Entity\Logo", cascade={"persist"})
     * @ORM\JoinColumn(name="id_logo", referencedColumnName="id")
     * @Assert\Valid
     */
    private $logo;

    /**
     * @var \DateTime $dateCreated
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="date_created", type="datetime")
     */
    private $dateCreated;

    /**
     * @var \DateTime $dateUpdate
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="date_update", type="datetime")
     */
    private $dateUpdate;


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
     * @return Brand
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
     * @return Brand
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
     * Set slug
     *
     * @param string $slug
     * @return Brand
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

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return Brand
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     * @return Brand
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime 
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    public function __toString()
    {

        return $this->title .', '. $this->dateCreated->format('d-m-Y');
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $message)
    {


        if ($this->getTitle() == "troiswa"){

            // pour le paramettre entre acolade , attention aux espaces ,
            // les deux valeur doivent etre identique  dans buildViolation et setParameter
            $message->buildViolation('{{ parameter }} n\'est pas valide! Le nom de la marque ne peu etre troiswa')
                ->atPath('title')
                ->setParameter("{{ parameter }}", $this->title)
                ->addViolation();
        }
    }

    /**
     * Add products
     *
     * @param \troiswa\BackBundle\Entity\Product $products
     * @return Brand
     */
    public function addProduct(\troiswa\BackBundle\Entity\Product $products)
    {
        $this->products[] = $products;

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
     * Set logo
     *
     * @param \troiswa\BackBundle\Entity\Logo $logo
     * @return Brand
     */
    public function setLogo(\troiswa\BackBundle\Entity\Logo $logo = null)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return \troiswa\BackBundle\Entity\Logo 
     */
    public function getLogo()
    {
        return $this->logo;
    }
}
