<?php

namespace Main\CommonBundle\Entity\Photographers;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Main\CommonBundle\Entity\Photographers\Devis as Devis;


/**
 * @ORM\EntityListeners({ "Main\CommonBundle\Listener\DevisBookListener" })
 * @ORM\Entity(repositoryClass="Main\CommonBundle\Entity\Photographers\DevisBookRepository")
 * @ORM\Table(name="photographers.devis_book")
 * @ORM\HasLifecycleCallbacks()
 */
class DevisBook
{

	/**
	 * @ORM\JoinColumn(name="devis", referencedColumnName="id")
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Photographers\Devis")
	 * @ORM\Id
	 */
    private $devis;

     /**
     * url
     * @var string
     * @ORM\Id
     * @ORM\Column(name="url", type="string", length=150, nullable=false)
     */
    private $url;

     /**
     * typeFichier
     * @var string
     *
     * @ORM\Column(name="type_fichier", type="string", length=30, nullable=false)
     */
    protected $fileType;
   
    /**
     * tailleFichier
     * @var integer
     *
     * @ORM\Column(name="taille_fichier", type="bigint", nullable=false)
     */
    private $fileSize;  
  
    /**
	 *
	 * @ORM\Column(columnDefinition="SMALLINT DEFAULT 0 NOT NULL")
	 *
	 */
    private $profile;

    /**
	 * @var datetime $createdAt
	 *
	 * @ORM\Column(name="createdAt", type="datetime")
	 */
	private $createdAt;

	/**
	 * [getDevis description]
	 * @return [type] [description]
	 */
	public function getDevis()
	{
		return $this->devis;
	}

	/**
	 * [setDevis description]
	 * @param Devis $devis [description]
	 */
	public function setDevis(Devis $devis)
	{
		$this->devis = $devis;
	}

	/**
	 * [getUrl description]
	 * @return [type] [description]
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * [setUrl description]
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * [getFileType description]
	 * @return [type] [description]
	 */
	public function getFileType()
	{
		return $this->fileType;
	}

	/**
	 * [setFileType description]
	 * @param [type] $fileType [description]
	 */
	public function setFileType($fileType)
	{
		$this->fileType = $fileType;
	}

	/**
	 * [getFileSize description]
	 * @return [type] [description]
	 */
	public function getFileSize()
	{
		return $this->fileSize;
	}

	/**
	 * [setFileSize description]
	 * @param [type] $fileSize [description]
	 */
	public function setFileSize($fileSize)
	{
		$this->fileSize = $fileSize;
	}
	
	/**
	 * [getProfile description]
	 * @return [type] [description]
	 */
	public function getProfile()
	{
		return $this->profile;
	}


	/**
	 * [setProfile description]
	 * @param [type] $profile [description]
	 */
	public function setProfile($profile)
	{
		$this->profile = $profile;
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
		$this->profile = 0;
		$this->createdAt = new \DateTime('now');
		//Ajout du status
	}

}