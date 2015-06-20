<?php

namespace Main\CommonBundle\Entity\Photographers;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Main\CommonBundle\Entity\Photographers\Devis as Devis;
use Main\CommonBundle\Entity\Utils\Duration as Duration;

/**
 * @ORM\Entity
 * @ORM\Table(name="photographers.devis_prices")
 * @ORM\Entity(repositoryClass="Main\CommonBundle\Entity\Photographers\DevisPricesRepository")
 */
class DevisPrices
{
	/**
	 * @ORM\JoinColumn(name="devis", referencedColumnName="id")
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Photographers\Devis", inversedBy="id", cascade={"remove"})
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="NONE")
	 */
	private $devis;

	/**
	 * @ORM\JoinColumn(name="duration", referencedColumnName="id")
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Utils\Duration", inversedBy="id", cascade={"remove"})
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="NONE")
	 */
	private $duration;

	/**
	 * @var float $price
	 *
	 * @ORM\Column(name="price", type="float", nullable=false, columnDefinition="FLOAT")
	 */
	private $price;

	/**
	 *
	 * @ORM\Column(columnDefinition="SMALLINT DEFAULT 1 NOT NULL")
	 */
	private $active;
	
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
	 * Return price
	 */
	public function getPrice()
	{
		return $this->price;
	}
	
	/**
	 *
	 * @param float $price
	 */
	public function setPrice($price)
	{
		$this->price = $price;
	}

	/**
	 * Return active
	 */
	public function getActive()
	{
		return $this->active;
	}
	
	/**
	 *
	 * @param tinyint $active
	 */
	public function setActive($active)
	{
		$this->active = $active;
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
	 * Transform to string
	 *
	 * @return string
	 */
	public function __toString()
	{
	    return (string) $this->getDevis()->getId();
	}
	
	public function __construct()
	{
		$this->active 	 = 1;
		$this->createdAt = new \DateTime('now');
		$this->updatedAt = new \DateTime('now');
		//Ajout du status
	}
}