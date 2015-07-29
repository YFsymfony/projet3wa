<?php

namespace troiswa\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="troiswa\FrontBundle\Entity\CommentRepository")
 */
class Comment
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
     * @ORM\ManyToOne(targetEntity="troiswa\FrontBundle\Entity\Client")
     * @ORM\JoinColumn(name="id_client",referencedColumnName="id")
     */
    private $author;

    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="troiswa\BackBundle\Entity\Product" , cascade={"persist","remove"})
     * @ORM\JoinColumn(name="id_product",referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $product;

    /**
     * @var string
     * @Assert\NotBlank(message=" Champ obligatoire ")
     * @Assert\Length(
     *      min = "10",
     *      max = "500",
     *      minMessage = "le nom de l'article doit être minimum de {{ limit }} caractères",
     *      maxMessage = "le nom de l'article doit être maximum de {{ limit }} caractères"
     * )
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var float
     * @Assert\Type(type="float", message="La valeur {{ value }} n'est pas un type {{ type }} valide. choississez une note de 1 à 5")
     * @Assert\GreaterThanOrEqual(value = 1)
     * @Assert\LessThanOrEqual(value = 5 )
     * @ORM\Column(name="note", type="float")
     */
    private $note;

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
     * @ORM\ManyToOne(targetEntity="Comment")
     * @ORM\JoinColumn(name="id_category",referencedColumnName="id")
     */
    private $parent;


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
     * Set content
     *
     * @param string $content
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set note
     *
     * @param float $note
     * @return Comment
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return float 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set product
     *
     * @param string $product
     * @return Comment
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return string 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Comment
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
     * @return Comment
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

    /**
     * Set author
     *
     * @param \troiswa\FrontBundle\Entity\Client $author
     * @return Comment
     */
    public function setAuthor(\troiswa\FrontBundle\Entity\Client $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \troiswa\FrontBundle\Entity\Client 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set parent
     *
     * @param \troiswa\FrontBundle\Entity\Comment $parent
     * @return Comment
     */
    public function setParent(\troiswa\FrontBundle\Entity\Comment $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \troiswa\FrontBundle\Entity\Comment 
     */
    public function getParent()
    {
        return $this->parent;
    }


}
