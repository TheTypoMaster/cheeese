<?php

namespace Main\CommonBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Main\CommonBundle\Entity\Users\User as User;
use Main\CommonBundle\Entity\Geo\Town as Town;
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
	 * @var bigint $id
	 *
	 * @ORM\Column(name="id", type="bigint", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;

	/**
	 * @ORM\OneToOne(targetEntity="Main\CommonBundle\Entity\Users\User", fetch="EAGER")
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
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Geo\Town", fetch="EAGER")
	 * @ORM\JoinColumn(name="town", referencedColumnName="id")
	 */
	private $town;

		/**
	 * @var text $lastname
	 *
	 * @ORM\Column(name="identification", type="string", length=50, nullable=false)
	 */
	private $identification;

	/**
	 * @var text $title
	 *
	 * @ORM\Column(name="studio", type="string", length=255, nullable=true)
	 */
	private $studio;

	/**
	 * @var text $address
	 *
	 * @ORM\Column(name="studio_address", type="string", length=255, nullable=true)
	 */
	private $studioAddress;

	/**
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Geo\Town", fetch="EAGER")
	 * @ORM\JoinColumn(name="studio_town", referencedColumnName="id", nullable=true)
	 */
	private $studioTown;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Status\PhotographerStatus", fetch="EAGER")
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
	public function getStudio()
	{
		return $this->studio;
	}
	
	/**
	 * 
	 * @param string $title
	 */
	public function setStudio($studio)
	{
		$this->studio = $studio;
	}

	/**
	 * 
	 */
	public function getStudioAddress()
	{
		return $this->studioAddress;
	}
	
	/**
	 * 
	 * @param string $address
	 */
	public function setStudioAddress($studioAddress)
	{
		$this->studioAddress = $studioAddress;
	}
	
	/**
	 *
	 */
	public function getStudioTown()
	{
		return $this->studioTown;
	}
	
	/**
	 *
	 * @param Town $town
	 */
	public function setStudioTown(Town $town = null)
	{
		$this->studioTown = $town;
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