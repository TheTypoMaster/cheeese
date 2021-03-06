<?php

namespace Main\CommonBundle\Entity\Users;
use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users.user")
 * @ORM\Entity(repositoryClass="Main\CommonBundle\Entity\Users\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\Regex(
     *  pattern="/^[a-zA-Z0-9]{5,50}+$/",
     *  message="form.user.field.username"
     * )
     */
    protected $username;

    /**
     * @Assert\Regex(
     *  pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{6,}/",
     *  message="form.user.field.plainPassword"
     * )
     */
    protected $plainPassword;
    
    /**
     * @var text $presentation
     *
     * @ORM\Column(name="presentation", type="text", nullable=true)
     */
    protected $presentation;
    
    /**
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    protected $firstName;
    
    /**
     * @ORM\Column(name="last_name",type="string", length=255, nullable=true)
     */
    protected $lastName;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $telephone;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $photo;

    /**
     * typeFichier
     * @var string
     *
     * @ORM\Column(name="type_photo", type="string", length=30, nullable=true)
     */
    protected $photoType;
    
    /**
     * @var float $note
     *
     * @ORM\Column(name="note", type="float", nullable=false, columnDefinition="FLOAT")
     */
    protected $note;
    
    /**
     * @ORM\Column(name="prestations", type="integer", nullable=false)
     */
    protected $prestations;
    
    /**
     * @var datetime $premiumEnd
     *
     * @ORM\Column(name="premium_end", type="datetime", nullable=true)
     */
    protected $premiumEnd;
    
    /**
     * @var datetime $birthDate
     *
     * @ORM\Column(name="birthdate", type="datetime", nullable=true)
     */
    protected $birthDate;
    
    /**
	 * @var datetime $createdAt
	 * 
	 * @ORM\Column(name="createdAt", type="datetime")
	 */
	private $createdAt;
	
	/**
	 * @var datetime $updatedAt
	 *
	 * @ORM\Column(name="updatedAt", type="datetime")
	 */
	private $updatedAt;
    
    /**
     * 
     */
    public function getPresentation()
    {
    	return $this->presentation;
    }
    
    /**
     * 
     * @param unknown $presentation
     */
    public function setPresentation($presentation)
    {
    	$this->presentation = $presentation;
    }
    
    /**
     * 
     */
	public function getTelephone()
	{
		return $this->telephone;
	}
	
	/**
	 * 
	 * @param unknown $telephone
	 */
	public function setTelephone($telephone)
	{
		$this->telephone = $telephone;
	}
	
	/**
	 *
	 */
	public function getPhoto()
	{
		return $this->photo;
	}
	
	/**
	 *
	 * @param unknown $photo
	 */
	public function setPhoto($photo)
	{
		$this->photo = $photo;
	}

	/**
	 * [getPhotoType description]
	 * @return [type] [description]
	 */
	public function getPhotoType()
	{
		return $this->photoType;
	}

	/**
	 * [setPhotoType description]
	 * @param [type] $photoType [description]
	 */
	public function setPhotoType($photoType)
	{
		$this->photoType = $photoType;
	}

	/**
	 *
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}
	
	/**
	 *
	 * @param unknown $firstName
	 */
	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	}
	
	/**
	 *
	 */
	public function getLastName()
	{
		return $this->lastName;
	}
	
	/**
	 *
	 * @param unknown lastName
	 */
	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
	}
	
	/**
	 *
	 */
	public function getNote()
	{
		return $this->note;
	}
	
	/**
	 *
	 * @param unknown $note
	 */
	public function setNote($note)
	{
		$this->note = $note;
	}
	
	/**
	 *
	 */
	public function getPrestations()
	{
		return $this->prestations;
	}
	
	/**
	 *
	 * @param unknown $prestations
	 */
	public function setPrestations($prestations)
	{
		$this->prestations = $prestations;
	}
	
	/**
	 *
	 */
	public function getPremiumEnd()
	{
		return $this->premiumEnd;
	}
	
	/**
	 *
	 * @param unknown $premiumEnd
	 */
	public function setPremiumEnd($premiumEnd)
	{
		$this->premiumEnd = $premiumEnd;
	}
	
	/**
	 *
	 */
	public function getBirthDate()
	{
		return $this->birthDate;
	}
	
	/**
	 *
	 * @param unknown $birthDate
	 */
	public function setBirthDate($birthDate)
	{
		$this->birthDate = $birthDate;
	}
	
	/**
	 * Set createdAt
	 *
	 * @param datetime $createdAt
	 */
	public function setCreatedAt($createdAt)
	{
		$this->createdAt = $createdAt;
	}
	
	/**
	 * Get createdAt
	 *
	 * @return datetime
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}
	
	/**
	 * Set updatedAt
	 *
	 * @param datetime $updatedAt
	 */
	public function setUpdatedAt($updatedAt)
	{
		$this->updatedAt = $updatedAt;
	}
	
	/**
	 * Get updatedAt
	 *
	 * @return datetime
	 */
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}
	
    public function __construct()
    {

        $this->note = 0;
        $this->prestations = 0;
        $this->createdAt = new \DateTime('now');
		$this->updatedAt = new \DateTime('now');
    	parent::__construct();
    }
}