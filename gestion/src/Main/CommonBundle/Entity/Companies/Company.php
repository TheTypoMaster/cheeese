<?php

namespace Main\CommonBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Main\CommonBundle\Entity\Users\User as User;
use Main\CommonBundle\Entity\Geo\Town as Town;
use Main\CommonBundle\Entity\Geo\Country as Country;
use Main\CommonBundle\Entity\Status\PhotographerStatus as Status;

/**
 * @ORM\EntityListeners({ "Main\CommonBundle\Listener\CompanyListener" })
 * @ORM\Table(name="companies.company")
 * @ORM\Entity(repositoryClass="Main\CommonBundle\Entity\Companies\CompanyRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Company
{
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Users\User", inversedBy="id")
	 * @ORM\JoinColumn(name="photographer", referencedColumnName="id")
	 */
	private $photographer;
	
	/**
	 * @var text $title
	 *
	 * @ORM\Column(name="title", type="string", length=255, nullable=false)
	 */
	private $title;
	
	/**
	 * @var text $address
	 *
	 * @ORM\Column(name="address", type="string", length=255, nullable=false)
	 */
	private $address;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Geo\Town", inversedBy="id")
	 * @ORM\JoinColumn(name="town", referencedColumnName="id")
	 */
	private $town;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Geo\Country", inversedBy="id")
	 * @ORM\JoinColumn(name="country", referencedColumnName="id")
	 */
	private $country;
	
	/**
	 * @var text $lastname
	 *
	 * @ORM\Column(name="identification", type="string", length=50, nullable=false, unique=true)
	 */
	private $identification;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Status\PhotographerStatus", inversedBy="id")
	 * @ORM\JoinColumn(name="status", referencedColumnName="id")
	 */
	private $status;
	
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
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * 
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
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
	 *
	 */
	public function getTown()
	{
		return $this->town;
	}
	
	/**
	 *
	 * @param Town $town
	 */
	public function setTown(Town $town)
	{
		$this->town = $town;
	}
	
	/**
	 *
	 */
	public function getCountry()
	{
		return $this->country;
	}
	
	/**
	 *
	 * @param Country $country
	 */
	public function setCountry(Country $country)
	{
		$this->country = $country;
	}
	
	/**
	 * 
	 */
	public function getIdentification()
	{
		return $this->identification;
	}
	
	/**
	 * 
	 * @param string $identification
	 */
	public function setIdentification($identification)
	{
		$this->identification = $identification;
	}
	
	/**
	 *
	 */
	public function getStatus()
	{
		return $this->status;
	}
	
	/**
	 *
	 * @param Status $status
	 */
	public function setStatus(Status $status)
	{
		$this->status = $status;
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