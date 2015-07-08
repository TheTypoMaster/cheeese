<?php

namespace Main\CommonBundle\Entity\Photographers;

use Doctrine\ORM\Mapping as ORM;
use Main\CommonBundle\Entity\Companies\Company as Company;

/**
 * @ORM\Entity
 * @ORM\Table(name="photographers.availability")
 * @ORM\Entity(repositoryClass="Main\CommonBundle\Entity\Photographers\AvailabilityRepository")
 */
class Availability
{	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Companies\Company")
	 * @ORM\JoinColumn(name="company", referencedColumnName="id")
	 */
	private $company;
	
	/**
	 * @var \DateTime
	 * @ORM\Id
	 * @ORM\Column(name="day", type="date", nullable=false)
	 */
	private $day;
	
	/**
	 * @var datetime $createdAt
	 *
	 * @ORM\Column(name="createdAt", type="datetime")
	 */
	private $createdAt;
	
	/**
	 * Return photographer
	 */
	public function getCompany()
	{
		return $this->company;
	}
	
	/**
	 *
	 * @param Company $company
	 */
	public function setCompany(Company $company)
	{
		$this->company = $company;
	}
	
	/**
	 * Return day
	 */
	public function getDay()
	{
		return $this->day;
	}
	
	/**
	 *
	 * @param date $day
	 */
	public function setDay($day)
	{
		$this->day = $day;
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
}