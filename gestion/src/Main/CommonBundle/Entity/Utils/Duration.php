<?php

namespace Main\CommonBundle\Entity\Utils;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="utils.duration")
 * @ORM\Entity(repositoryClass="Main\CommonBundle\Entity\Utils\DurationRepository")
 */
class Duration
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
	 * 
	 * @var text $libelle
	 * @ORM\Column(name="libelle", type="string", length=255, nullable=false)
	 */
	private $libelle;
	
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
	 * Return Libelle
	 */
	public function getLibelle()
	{
		return $this->libelle;
	}
	
	/**
	 *
	 * @param unknown $libelle
	 */
	public function setLibelle($libelle)
	{
		$this->libelle = $libelle;
	}
}