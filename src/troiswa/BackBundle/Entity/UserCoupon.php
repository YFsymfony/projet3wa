<?php
namespace troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Coupon
 *
 * @ORM\Table(name="user_coupon")
 * @ORM\Entity(repositoryClass="troiswa\BackBundle\Repository\UserCouponRepository")
 */
class UserCoupon
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="troiswa\BackBundle\Entity\User" , inversedBy="coupon")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;


    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="troiswa\BackBundle\Entity\Coupon")
     * @ORM\JoinColumns({
     *      @ORM\JoinColumn(name="coupon_id", referencedColumnName="id")
     * })
     */
    private $coupon;

    /**
     * @var boolean
     * @ORM\Column(name="used", type="boolean")
     */
    private $used;

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
     * Set user
     *
     * @param \troiswa\BackBundle\Entity\User $user
     * @return UserCoupon
     */
    public function setUser(\troiswa\BackBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \troiswa\BackBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set coupon
     *
     * @param \troiswa\BackBundle\Entity\Coupon $coupon
     * @return UserCoupon
     */
    public function setCoupon(\troiswa\BackBundle\Entity\Coupon $coupon)
    {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * Get coupon
     *
     * @return \troiswa\BackBundle\Entity\Coupon
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     * Set used
     *
     * @param boolean $used
     * @return UserCoupon
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * Get used
     *
     * @return boolean 
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return UserCoupon
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
     * @return UserCoupon
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
