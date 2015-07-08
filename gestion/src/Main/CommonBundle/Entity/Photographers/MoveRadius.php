<?php

namespace Main\CommonBundle\Entity\Photographers;

use Doctrine\ORM\Mapping as ORM;
use Main\CommonBundle\Entity\Companies\Company as Company;

/**
 * @ORM\Entity
 * @ORM\EntityListeners({ "Main\CommonBundle\Listener\MoveRadiusListener" })
 * @ORM\Table(name="photographers.moves_radius")
 * @ORM\HasLifecycleCallbacks
 */
class MoveRadius
{

	/**
	 * @ORM\Id
	 * @ORM\OneToOne(targetEntity="Main\CommonBundle\Entity\Companies\Company")
	 * @ORM\JoinColumn(name="company", referencedColumnName="id")
	 */
	private $company;
	
	/**
	 * @ORM\Column(name="radius", type="integer", nullable=false)
	 */
	private $radius;
	
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
	 *
	 */
	public function getRadius()
	{
		return $this->radius;
	}
	
	/**
	 *
	 * @param integer $radius
	 */
	public function setRadius($radius)
	{
		$this->radius = $radius;
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