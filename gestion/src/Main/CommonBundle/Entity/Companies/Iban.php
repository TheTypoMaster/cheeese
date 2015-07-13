<?php

namespace Main\CommonBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;
use Main\CommonBundle\Entity\Users\User as User;

/**
 * @ORM\EntityListeners({ "Main\CommonBundle\Listener\IbanListener" })
 * @ORM\Table(name="companies.iban")
 * @ORM\Entity(repositoryClass="Main\CommonBundle\Entity\Companies\IbanRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Iban
{

	/**
	 * @var bigint $id
	 *
	 * @ORM\Column(name="id", type="bigint", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\OneToOne(targetEntity="Main\CommonBundle\Entity\Users\User")
	 * @ORM\JoinColumn(name="photographer", referencedColumnName="id")
	 */
	private $photographer;
	
	/**
	 * @var text $name
	 *
	 * @ORM\Column(name="name", type="string", length=255, nullable=false)
	 */
	private $name;

	/**
	 * @var text $address
	 *
	 * @ORM\Column(name="address", type="string", length=255, nullable=false)
	 */
	private $address;

	/**
	 * @var text $name
	 *
	 * @ORM\Column(name="iban", type="string", length=255, nullable=false)
	 */
	private $iban;

	/**
	 * @var text $name
	 *
	 * @ORM\Column(name="bic", type="string", length=255, nullable=false)
	 */
	private $bic;

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
	 * Return Id
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * 
	 */
	public function getPhotographer()
	{
		return $this->photographer;
	}
	
	/**
	 * 
	 * @param User $photographer
	 */
	public function setPhotographer(User $photographer)
	{
		$this->photographer = $photographer;
	}

	/**
	 * 
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 * 
	 * @param string $title
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * 
	 */
	public function getIban()
	{
		return $this->iban;
	}
	
	/**
	 * 
	 * @param string $title
	 */
	public function setIban($iban)
	{
		$this->iban = $iban;
	}

	/**
	 * 
	 */
	public function getBic()
	{
		return $this->bic;
	}
	
	/**
	 * 
	 * @param string $title
	 */
	public function setBic($bic)
	{
		$this->bic = $bic;
	}
	
	/**
	 * 
	 */
	public function getAddress()
	{
		return $this->address;
	}
	
	/**
	 * 
	 * @param string $address
	 */
	public function setAddress($address)
	{
		$this->address = $address;
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
	
	/**
	 * 
	 */
	public function __construct()
	{
		$this->createdAt = new \DateTime('now');
		$this->updatedAt = new \DateTime('now');
		//Ajout du status
	}

}