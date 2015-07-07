<?php

namespace Main\CommonBundle\Entity\Messages;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

use Main\CommonBundle\Entity\Prestations\Prestation as Prestation;
use Main\CommonBundle\Entity\Users\User as User;


/**
 * @ORM\Table(name="messages.message")
 * @ORM\Entity(repositoryClass="Main\CommonBundle\Entity\Messages\MessageRepository")
 */
class Message
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
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Users\User")
	 * @ORM\JoinColumn(name="sender", referencedColumnName="id")
	 */
	private $sender;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Users\User")
	 * @ORM\JoinColumn(name="receiver", referencedColumnName="id")
	 */
	private $receiver;
	
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="type", type="smallint", nullable=false)
	 */
	private $type;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Prestations\Prestation", fetch="EAGER")
	 * @ORM\JoinColumn(name="prestation", referencedColumnName="id", nullable=true)
	 */
	private $prestation;
	
	/**
	 * @var boolean
	 *
	 * @ORM\Column(columnDefinition="SMALLINT DEFAULT 0 NOT NULL")
	 */
	private $read;
	
	/**
	 * @ORM\Column(name="content", type="text", nullable=false)
	 */
	private $content;
	
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
	public function getSender()
	{
		return $this->sender;
	}
	
	/**
	 * 
	 * @param User $sender
	 */
	public function setSender(User $sender)
	{
		$this->sender = $sender;
	}
	
	/**
	 *
	 */
	public function getReceiver()
	{
		return $this->receiver;
	}
	
	/**
	 *
	 * @param User $receiver
	 */
	public function setReceiver(User $receiver)
	{
		$this->receiver = $receiver;
	}
	
	/**
	 * Return active
	 */
	public function getType()
	{
		return $this->type;
	}
	
	/**
	 *
	 * @param tinyint $active
	 */
	public function setType($type)
	{
		$this->type = $type;
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
	 * Return read
	 */
	public function getRead()
	{
		return $this->read;
	}
	
	/**
	 *
	 * @param tinyint $read
	 */
	public function setRead($read)
	{
		$this->read = $read;
	}
	
	/**
	 * Return read
	 */
	public function getContent()
	{
		return $this->content;
	}
	
	/**
	 *
	 * @param text $content
	 */
	public function setContent($content)
	{
		$this->content = $content;
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
		$this->read      = 0;
		$this->createdAt = new \DateTime('now');
		$this->updatedAt = new \DateTime('now');
	}
}