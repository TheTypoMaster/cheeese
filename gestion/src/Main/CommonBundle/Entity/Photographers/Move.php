<?php

namespace Main\CommonBundle\Entity\Photographers;

use Doctrine\ORM\Mapping as ORM;
use Main\CommonBundle\Entity\Companies\Company as Company;
use Main\CommonBundle\Entity\Geo\Town as Town;

/**
 * @ORM\Entity
 * @ORM\Table(name="photographers.moves")
 * @ORM\Entity(repositoryClass="Main\CommonBundle\Entity\Photographers\MoveRepository")
 */
class Move
{
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Companies\Company", inversedBy="photographer")
	 * @ORM\JoinColumn(name="company", referencedColumnName="photographer")
	 */
	private $company;
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Geo\Town", inversedBy="id")
	 * @ORM\JoinColumn(name="town", referencedColumnName="id")
	 */
	private $town;
	
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