<?php

namespace Main\CommonBundle\Entity\Prestations;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

use Main\CommonBundle\Entity\Photographers\Devis as Devis;
use Main\CommonBundle\Entity\Users\User as User;
use Main\CommonBundle\Entity\Geo\Town as Town;
use Main\CommonBundle\Entity\Status\PrestationStatus as Status;
use Main\CommonBundle\Entity\Utils\Duration as Duration;

/**
 * @ORM\EntityListeners({ "Main\CommonBundle\Listener\PrestationListener" })
 * @ORM\Table(name="prestations.prestation")
 * @ORM\Entity(repositoryClass="Main\CommonBundle\Entity\Prestations\PrestationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Prestation
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
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Photographers\Devis", inversedBy="id", cascade={"remove"})
	 * @ORM\JoinColumn(name="devis", referencedColumnName="id")
	 */
	private $devis;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Users\User", inversedBy="id", cascade={"remove"})
	 * @ORM\JoinColumn(name="client", referencedColumnName="id")
	 */
	private $client;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Geo\Town", inversedBy="id", cascade={"remove"})
	 * @ORM\JoinColumn(name="town", referencedColumnName="id")
	 */
	private $town;
	
	/**
	 * @var float $price
	 *
	 * @ORM\Column(name="price", type="float", nullable=false, columnDefinition="FLOAT")
	 */
	private $price;
	
	/**
	 * @var text $address
	 *
	 * @ORM\Column(name="address", type="string", length=255, nullable=false)
	 */
	private $address;
	
	/**
	 * @var datetime $createdAt
	 *
	 * @ORM\Column(name="start_time", type="datetime", nullable=false)
	 */
	private $startTime;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Utils\Duration", inversedBy="id", cascade={"remove"})
	 * @ORM\JoinColumn(name="duration", referencedColumnName="id")
	 */
	private $duration;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Status\PrestationStatus", inversedBy="id", cascade={"remove"})
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
	 * @param unknown $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}
	
	/**
	 * 
	 */
	public function getDevis()
	{
		return $this->devis;
	}
	/**
	 * 
	 * @param Devis $devis
	 */
	public function setDevis(Devis $devis)
	{
		$this->devis = $devis;
	}
	
	/**
	 *
	 */
	public function getClient()
	{
		return $this->client;
	}
	
	/**
	 *
	 * @param User $client
	 */
	public function setClient(User $client)
	{
		$this->client = $client;
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
	 * Return price
	 */
	public function getPrice()
	{
		return $this->price;
	}
	
	/**
	 *
	 * @param $day
	 */
	public function setPrice($price)
	{
		$this->price = $price;
	}
	
	/**
	 * Return address
	 */
	public function getAddress()
	{
		return $this->address;
	}
	
	/**
	 *
	 * @param $address
	 */
	public function setAddress($address)
	{
		$this->address = $address;
	}
	
	/**
	 * Return day
	 */
	public function getStartTime()
	{
		return $this->startTime;
	}
	
	/**
	 *
	 * @param date $day
	 */
	public function setStartTime($startTime)
	{
		$this->startTime = $startTime;
	}
	
	/**
	 * Return duration
	 */
	public function getDuration()
	{
		return $this->duration;
	}
	
	/**
	 *
	 * @param Duration $duration
	 */
	public function setDuration(Duration $duration)
	{
		$this->duration = $duration;
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
	
	
	
	public function __construct()
	{
		$this->createdAt = new \DateTime('now');
		$this->updatedAt = new \DateTime('now');
	}
}