<?php

namespace troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use troiswa\BackBundle\Validator\phone;
use troiswa\BackBundle\Validator\passwordChecker;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="troiswa\BackBundle\Repository\UserRepository")
 * @UniqueEntity("email")
 * @UniqueEntity("pseudo")
 */
class User
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
     * @ORM\Column(name="firstname", type="string", length=100)
     * @Assert\Length(
     *      min = "2",
     *      max = "100",
     *      minMessage = "le nom de la category doit etre minimum de {{ limit }} caractères",
     *      maxMessage = "le nom de la category doit etre maximum de {{ limit }} caractères"
     * )
     */
    private $firstname;

    /**
     * @var string
     * @Assert\NotBlank(message=" Champ obligatoire ")
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\Length(
     *      min = "2",
     *      max = "255",
     *      minMessage = "le nom de la category doit etre minimum de {{ limit }} caractères",
     *      maxMessage = "le nom de la category doit etre maximum de {{ limit }} caractères"
     * )
     */
    private $lastname;

    /**
     * @var string
     * @Assert\NotBlank(message=" Champ obligatoire ")
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255 , unique=true)
     */
    private $email;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message=" Champ obligatoire ")
     * @ORM\Column(name="birthday", type="dateTime")
     */
    private $birthday;

    /**
     * @var string
     * @phone()
     * @Assert\NotBlank(message=" Champ obligatoire ")
     * @ORM\Column(name="telephone", type="string", length=255)
     */
    private $telephone;

    /**
     * @var string
     * @Assert\NotBlank(message=" Champ obligatoire ")
     * @ORM\Column(name="pseudo", type="string", length=100 , unique=true)
     */
    private $pseudo;

    /**
     * @var string
     * @Assert\NotBlank(message=" Champ obligatoire ")
     * @ORM\Column(name="adress", type="text")
     */
    private $adress;

    /**
     * @var string
     * @Assert\NotBlank(message=" Champ obligatoire ")
     * @passwordChecker(regex="^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{4,8}", minimum=10)
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;


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
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return User
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set pseudo
     *
     * @param string $pseudo
     * @return User
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo
     *
     * @return string 
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set adress
     *
     * @param string $adress
     * @return User
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string 
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $message)
    {


        if (strtolower($this->getPseudo()) == "admin"){

            // pour le paramettre entre acolade , attention aux espaces ,
            // les deux valeur doivent etre identique  dans buildViolation et setParameter
            $message->buildViolation('{{ parameter }} n\'est pas valide! Vous ne pouvez pas utiliser Admin ou admin')
                ->atPath('pseudo')
                ->setParameter("{{ parameter }}", $this->pseudo)
                ->addViolation();
        }
    }
}
