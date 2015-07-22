<?php
namespace troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
}
