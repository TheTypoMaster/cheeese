<?php

namespace Main\CommonBundle\Entity\Prestations;

use Doctrine\ORM\Mapping as ORM;
use Main\CommonBundle\Entity\Users\User as User;
use Main\CommonBundle\Entity\Prestations\Prestation as Prestation;

/**
 * @ORM\Entity
 * @ORM\Table(name="prestations.evaluation")
 */
class Evaluation
{
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Users\User", inversedBy="id", cascade={"remove"})
	 * @ORM\JoinColumn(name="scorer", referencedColumnName="id")
	 */
	private $scorer;
	
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Prestations\Prestation", inversedBy="id", cascade={"remove"})
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
	 * @ORM\Column(name="user_notation", type="integer", nullable=true)
	 */
	private $user_notation;
	
	/**
	 * @ORM\Column(name="user_comment", type="text", nullable=true)
	 */
	private $user_comment;
	
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
	public function getScorer()
	{
		return $this->scorer;
	}
	
	/**
	 *
	 * @param User $user
	 */
	public function setScorer(User $scorer)
	{
		$this->scorer = $scorer;
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
	public function getUserNotation()
	{
		return $this->user_notation;
	}
	
	/**
	 *
	 * @param integer $user_notation
	 */
	public function setUserNotation($user_notation)
	{
		$this->user_notation = $user_notation;
	}

	/**
	 *
	 */
	public function getUserComment()
	{
		return $this->user_comment;
	}
	
	/**
	 *
	 * @param text $user_comment
	 */
	public function setUserComment($user_comment)
	{
		$this->user_comment = $user_comment;
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