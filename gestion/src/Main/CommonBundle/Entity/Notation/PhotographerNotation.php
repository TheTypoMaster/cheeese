<?php

namespace Main\CommonBundle\Entity\Notation;

use Doctrine\ORM\Mapping as ORM;
use Main\CommonBundle\Entity\Users\User as User;
use Main\CommonBundle\Entity\Prestations\Prestation as Prestation;

/**
 * @ORM\Entity
 * @ORM\Table(name="notations.photographer")
 */
class PhotographerNotation
{
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Users\User", inversedBy="id")
	 * @ORM\JoinColumn(name="photographer", referencedColumnName="id")
	 */
	private $photographer;
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Prestations\Prestation", inversedBy="id")
	 * @ORM\JoinColumn(name="prestation", referencedColumnName="id")
	 */
	private $prestation;
	
	/**
	 * @ORM\Column(name="client_notation", type="integer", nullable=true)
	 */
	private $client_notation;
	
	/**
	 * @ORM\Column(name="client_comment", type="text", nullable=true)
	 */
	private $client_comment;
	
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
	public function getPhotographer()
	{
		return $this->photographer;
	}
	
	/**
	 *
	 * @param User $photographer
	 */
	public function setPhotographer(User $photographer)
	{
		$this->photographer = $photographer;
	}
	
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
	public function getClientNotation()
	{
		return $this->client_notation;
	}
	
	/**
	 *
	 * @param integer $client_notation
	 */
	public function setClientNotation($client_notation)
	{
		$this->client_notation = $client_notation;
	}
	
	/**
	 *
	 */
	public function getClientComment()
	{
		return $this->client_comment;
	}
	
	/**
	 *
	 * @param text $client_comment
	 */
	public function setClientComment($client_comment)
	{
		$this->client_comment = $client_comment;
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