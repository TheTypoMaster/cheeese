<?php

namespace Main\CommonBundle\Entity\Geo;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="geo.town")
 * @ORM\Entity(repositoryClass="Main\CommonBundle\Entity\Geo\TownRepository")
 */
class Town
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
	 * @var text $name
	 *
	 * @ORM\Column(name="name", type="string", length=255, nullable=false)
	 */
	private $name;
	
	/**
	 * @var string $code
	 *
	 * @ORM\Column(name="code", type="string", length=255, nullable=false)
	 */
	private $code;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Country", inversedBy="id", cascade={"remove"})
	 * @ORM\JoinColumn(name="country", referencedColumnName="id")
	 */
	private $country;
	
	/**
	 * @ORM\Column(name="latitude", type="decimal", precision=9, scale=7, nullable=false)
	 */
	private $latitude;
	
	/**
	 * @ORM\Column(name="longitude", type="decimal", precision=9, scale=7, nullable=false)
	 */
	private $longitude;
	
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
	 * Return Latitude
	 */
	public function getLatitude()
	{
		return $this->latitude;
	}
	
	/**
	 *
	 * @param unknown $latitude
	 */
	public function setLatitude($latitude)
	{
		$this->latitude = $latitude;
	}
	
	/**
	 * Return Longitude
	 */
	public function getLongitude()
	{
		return $this->longitude;
	}
	
	/**
	 *
	 * @param unknown $longitude
	 */
	public function setLongitude($longitude)
	{
		$this->longitude = $longitude;
	}
	
}
