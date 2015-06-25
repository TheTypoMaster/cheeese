<?php

namespace Main\CommonBundle\Entity\Notation;

use Doctrine\ORM\Mapping as ORM;
use Main\CommonBundle\Entity\Users\User as User;
use Main\CommonBundle\Entity\Prestations\Prestation as Prestation;

/**
 * @ORM\Entity
 * @ORM\Table(name="notations.client")
 */
class ClientNotation
{
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Users\User", inversedBy="id")
	 * @ORM\JoinColumn(name="client", referencedColumnName="id")
	 */
	private $client;
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Prestations\Prestation", inversedBy="id")
	 * @ORM\JoinColumn(name="prestation", referencedColumnName="id")
	 */
	private $prestation;
	
	/**
	 * @ORM\Column(name="prestation_notation", type="integer", nullable=true)
	 */
	private $prestation_notation;
	
	/**
	 * @ORM\Column(name="prestation_comment", type="text", nullable=true)
	 */
	private $prestation_comment;
	
	/**
	 * @ORM\Column(name="photographer_notation", type="integer", nullable=true)
	 */
	private $photographer_notation;
	
	/**
	 * @ORM\Column(name="photographer_comment", type="text", nullable=true)
	 */
	private $photographer_comment;
	
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
	public function getPrestation()
	{
		return $this->prestation;
	}
	
	/**
	 *
	 * @param User $prestation
	 */
	public function setPrestation(Prestation $prestation)
	{
		$this->prestation = $prestation;
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
	public function getPrestationNotation()
	{
		return $this->prestation_notation;
	}
	
	/**
	 *
	 * @param integer $prestation_notation
	 */
	public function setPrestationNotation($prestation_notation)
	{
		$this->prestation_notation = $prestation_notation;
	}

	/**
	 *
	 */
	public function getPrestationComment()
	{
		return $this->prestation_comment;
	}
	
	/**
	 *
	 * @param text $prestation_comment
	 */
	public function setPrestationComment($prestation_comment)
	{
		$this->prestation_comment = $prestation_comment;
	}
	
	/**
	 *
	 */
	public function getPhotographerNotation()
	{
		return $this->photographer_notation;
	}
	
	/**
	 *
	 * @param integer $prestation_notation
	 */
	public function setPhotographerNotation($photographer_notation)
	{
		$this->photographer_notation = $photographer_notation;
	}

	/**
	 *
	 */
	public function getPhotographerComment()
	{
		return $this->photographer_comment;
	}
	
	/**
	 *
	 * @param text $photographer_comment
	 */
	public function setPhotographerComment($photographer_comment)
	{
		$this->photographer_comment = $photographer_comment;
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