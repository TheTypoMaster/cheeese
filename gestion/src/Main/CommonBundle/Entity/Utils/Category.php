<?php

namespace Main\CommonBundle\Entity\Utils;

use Doctrine\ORM\Mapping as ORM;
use Main\CommonBundle\Entity\Geo\Country as Country;


/**
 * @ORM\Entity
 * @ORM\Table(name="utils.category")
 */
class Category
{
	const PARTICULIER = 1;
	const ENTREPRISE = 2;
	
	/**
	 * @var bigint $id
	 *
	 * @ORM\Column(name="id", type="bigint", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	private $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Geo\Country")
	 * @ORM\JoinColumn(name="country", referencedColumnName="id")
	 */
	private $country;
	
	/**
	 * 
	 * @var smallint$type
	 * @ORM\Column(name="type", type="smallint", nullable=false)
	 */
	private $type;
	
	/**
	 * @var text $name
	 *
	 * @ORM\Column(name="name", type="string", length=255, nullable=false)
	 */
	private $name;
	
	/**
	 * @var text $icon
	 *
	 * @ORM\Column(name="icon", type="string", length=255, nullable=false)
	 */
	private $icon;
	
	/**
	 *
	 * @ORM\Column(columnDefinition="SMALLINT DEFAULT 1 NOT NULL")
	 *
	 */
	private $active;	
	
	
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
	 * Return type
	 */
	public function getType()
	{
		return $this->type;
	}
	
	/**
	 *
	 * @param smallint $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * Return type
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 *
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}
	
	/**
	 * Return icon
	 */
	public function getIcon()
	{
		return $this->icon;
	}
	
	/**
	 *
	 * @param string $icon
	 */
	public function setIcon($icon)
	{
		$this->icon = $icon;
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
	 * If Category is active
	 * @return boolean
	 */
	public function isActived()
	{
		return $this->active === 1;
	}
}