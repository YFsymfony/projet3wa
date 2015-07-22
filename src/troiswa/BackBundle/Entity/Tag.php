<?php

namespace troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="troiswa\BackBundle\Repository\TagRepository")
 */
class Tag
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
     * @ORM\Column(name="word", type="string", length=50)
     */
    private $word;

    /**
     * @ORM\ManyToMany(targetEntity="troiswa\BackBundle\Entity\Product", mappedBy="tag")
     */
    private $product;


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
     * Set word
     *
     * @param string $word
     * @return Tag
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return string 
     */
    public function getWord()
    {
        return $this->word;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add product
     *
     * @param \troiswa\BackBundle\Entity\Product $product
     * @return Tag
     */
    public function addProduct(\troiswa\BackBundle\Entity\Product $product)
    {
        $this->product[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \troiswa\BackBundle\Entity\Product $product
     */
    public function removeProduct(\troiswa\BackBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
