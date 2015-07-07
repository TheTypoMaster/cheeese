<?php

namespace Main\CommonBundle\Entity\Geo;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="geo.country")
 * @ORM\Entity(repositoryClass="Main\CommonBundle\Entity\Geo\CountryRepository")
 */
class Country
{
	/**
	 * @var bigint $id
	 *
	 * @ORM\Column(name="id", type="bigint", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	
	/**
	 * @var text $name
	 *
	 * @ORM\Column(name="name", type="string", length=255, nullable=false)
	 */
	private $name;
	
	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $icon;
	
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
	 * Return Icon
	 */
	public function getIcon()
	{
		return $this->icon;
	}
	
	public function setIcon($icon)
	{
		$this->icon = $icon;
	}
	
}