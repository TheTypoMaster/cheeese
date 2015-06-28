<?php

namespace Main\CommonBundle\Entity\Companies;

use Doctrine\ORM\Mapping as ORM;
use Main\CommonBundle\Entity\Users\User as User;
use Main\CommonBundle\Entity\Prestations\Prestation as Prestation;



/**
 * @ORM\Entity
 * @ORM\Table(name="companies.transactions")
 */
class Transaction
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
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Users\User", inversedBy="id")
	 * @ORM\JoinColumn(name="photographer", referencedColumnName="id")
	 */
	private $photographer;

	/**
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Prestations\Prestation", inversedBy="id")
	 * @ORM\JoinColumn(name="prestation", referencedColumnName="id", nullable=true)
	 */
	private $prestation;

	/**
	 * @var float $price
	 *
	 * @ORM\Column(name="price", type="float", nullable=false, columnDefinition="FLOAT")
	 */
	private $price;

	/**
	 * @var text $name
	 *
	 * @ORM\Column(name="iban", type="string", length=255, nullable=false)
	 */
	private $iban;

	/**
	 * @ORM\Column(name="comment", type="text", nullable=false)
	 */
	private $comment;

	/**
	 * @var datetime $createdAt
	 * 
	 * @ORM\Column(name="createdAt", type="datetime")
	 */
	private $createdAt;
	
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
	 * @param Prestation $prestation
	 */
	public function setPrestation(Prestation $prestation)
	{
		$this->prestation = $prestation;
	}

	/**
	 * Return price
	 */
	public function getPrice()
	{
		return $this->price;
	}
	
	/**
	 *
	 * @param float $price
	 */
	public function setPrice($price)
	{
		$this->price = $price;
	}

	/**
	 * 
	 */
	public function getIban()
	{
		return $this->iban;
	}
	
	/**
	 * 
	 * @param string $title
	 */
	public function setIban($iban)
	{
		$this->iban = $iban;
	}


	/**
	 * Return read
	 */
	public function getComment()
	{
		return $this->comment;
	}
	
	/**
	 *
	 * @param text $content
	 */
	public function setComment($comment)
	{
		$this->comment = $comment;
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
	 * 
	 */
	public function __construct()
	{
		$this->createdAt = new \DateTime('now');
		//Ajout du status
	}
}