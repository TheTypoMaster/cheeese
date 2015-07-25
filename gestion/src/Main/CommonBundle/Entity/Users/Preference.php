<?php

namespace Main\CommonBundle\Entity\Users;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\EntityListeners({ "Main\CommonBundle\Listener\EmailListener" })
 * @ORM\Table(name="users.emails")
 * @ORM\Entity(repositoryClass="Main\CommonBundle\Entity\Users\PreferenceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Preference 
{
    /**
	 * @var bigint $id	 *
	 * @ORM\Column(name="id", type="bigint", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="IDENTITY")
	 */
	protected $id;

	/**
	 * @ORM\OneToOne(targetEntity="Main\CommonBundle\Entity\Users\User")
	 * @ORM\JoinColumn(name="entity", referencedColumnName="id")
	 */
	protected $user;

    /**
	 *
	 * @ORM\Column(columnDefinition="SMALLINT DEFAULT 1 NOT NULL")
	 *
	 */
    protected $prestation;

    /**
	 *
	 * @ORM\Column(columnDefinition="SMALLINT DEFAULT 1 NOT NULL")
	 *
	 */
    protected $messages;

    /**
	 *
	 * @ORM\Column(columnDefinition="SMALLINT DEFAULT 1 NOT NULL")
	 *
	 */
    protected $newsletter;
    
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
     */
    public function getUser()
    {
    	return $this->user;
    }
    
    /**
     * 
     * @param unknown $user
     */
    public function setUser(User $user)
    {
    	$this->user = $user;
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
	 * @param unknown $prestation
	 */
	public function setPrestation($prestation)
	{
		$this->prestation = $prestation;
	}
	
	/**
	 *
	 */
	public function getMessages()
	{
		return $this->messages;
	}
	
	/**
	 *
	 * @param unknown $messages
	 */
	public function setMessages($messages)
	{
		$this->messages = $messages;
	}

	/**
	 * [getPhotoType description]
	 * @return [type] [description]
	 */
	public function getNewsletter()
	{
		return $this->newsletter;
	}

	/**
	 * [setPhotoType description]
	 * @param [type] $photoType [description]
	 */
	public function setNewsletter($newsletter)
	{
		$this->newsletter = $newsletter;
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
    	$this->prestation 	= 1;
    	$this->messages 	= 1;
    	$this->newsletter 	= 1;
        $this->createdAt 	= new \DateTime('now');
		$this->updatedAt 	= new \DateTime('now');
    }
}