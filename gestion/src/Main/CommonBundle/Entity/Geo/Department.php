<?php

namespace Main\CommonBundle\Entity\Geo;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="geo.department")
 * @ORM\Entity(repositoryClass="Main\CommonBundle\Entity\Geo\DepartmentRepository")
 */
class Department
{
	/**
	 * @var string $id
	 *
	 * @ORM\Column(name="code", type="string", length=5, nullable=false)
	 * @ORM\Id
	 */
	private $code;

	/**
	 * @ORM\ManyToOne(targetEntity="Country")
	 * @ORM\JoinColumn(name="country", referencedColumnName="id")
	 * @ORM\Id
	 */
	private $country;
	
	/**
	 * @var text $name
	 *
	 * @ORM\Column(name="name", type="string", length=255, nullable=false)
	 */
	private $name;
	
	/**
	 * @var 
	 *
	 * @ORM\Column(columnDefinition="SMALLINT DEFAULT 0 NOT NULL")
	 */
	private $active;

	/**
	 * Return Code
	 */
	public function getCode()
	{
		return $this->code;
	}
	
	/**
	 *
	 * @param unknown $code
	 */
	public function setCode($code)
	{
		$this->code = $code;
	}
	
	/**
	 * Return Country
	 */
	public function getCountry()
	{
		return $this->country;
	}
	
	/**
	 *
	 * @param Countries $country
	 */
	public function setCountry(Country $country)
	{
		$this->country = $country;
	}
	
	/**
	 * Return Name
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 *
	 * @param unknown $name
	 */
	public function setName($name)
	{
		$this->name = $name;
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
	 * @param unknown $active
	 */
	public function setActive($active)
	{
		$this->active = $active;
	}
	
}
