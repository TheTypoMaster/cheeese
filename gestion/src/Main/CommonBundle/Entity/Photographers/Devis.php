<?php

namespace Main\CommonBundle\Entity\Photographers;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Main\CommonBundle\Entity\Companies\Company as Company;
use Main\CommonBundle\Entity\Utils\Category as Category;
use Main\CommonBundle\Entity\Utils\Currency as Currency;
use Main\CommonBundle\Entity\Utils\Duration as Duration;

/**
 * @ORM\EntityListeners({ "Main\CommonBundle\Listener\DevisListener" })
 * @ORM\Table(name="photographers.devis")
 * @ORM\Entity(repositoryClass="Main\CommonBundle\Entity\Photographers\DevisRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Devis
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
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Companies\Company", fetch="EAGER")
	 * @ORM\JoinColumn(name="company", referencedColumnName="id")
	 */
	private $company;
	
	/**
	 * @var text $title
	 *
	 * @ORM\Column(name="title", type="string", length=255, nullable=false)
	 */
	private $title;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Utils\Category", fetch="EAGER")
	 * @ORM\JoinColumn(name="category", referencedColumnName="id")
	 */
	private $category;
	
	/**
	 * @var text $presentation
	 *
	 * @ORM\Column(name="presentation", type="text")
	 */
	private $presentation;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Main\CommonBundle\Entity\Utils\Currency", fetch="EAGER")
	 * @ORM\JoinColumn(name="currency", referencedColumnName="id")
	 */
	private $currency;
	
	/**
	 *
	 * @ORM\Column(columnDefinition="SMALLINT DEFAULT 0 NOT NULL")	
	 */
	private $directpay;
	
	/**
	 * @var float $note
	 *
	 * @ORM\Column(name="note", type="float", nullable=false, columnDefinition="FLOAT")
	 */
	protected $note;
	
	/**
	 * @ORM\Column(name="prestations", type="integer", nullable=false)
	 */
	protected $prestations;
	
	/**
	 *
	 * @ORM\Column(columnDefinition="SMALLINT DEFAULT 1 NOT NULL")
	 *
	 */
	private $active;
	
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
	public function getCompany()
	{
		return $this->company;
	}
	
	/**
	 * 
	 * @param Company $company
	 */
	public function setCompany(Company $company)
	{
		$this->company = $company;
	}
	
	/**
	 * 
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * 
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	/**
	 *
	 */
	public function getCategory()
	{
		return $this->category;
	}
	
	/**
	 *
	 * @param Category $category
	 */
	public function setCategory(Category $category)
	{
		$this->category = $category;
	}

	/**
	 * Return type
	 */
	public function getPresentation()
	{
		return $this->presentation;
	}
	
	/**
	 *
	 * @param text $presentation
	 */
	public function setPresentation($presentation)
	{
		$this->presentation = $presentation;
	}

	/**
	 *
	 */
	public function getCurrency()
	{
		return $this->currency;
	}
	
	/**
	 *
	 * @param Currency $currency
	 */
	public function setCurrency(Currency $currency)
	{
		$this->currency = $currency;
	}

	/**
	 * Return active
	 */
	public function getDirectPay()
	{
		return $this->directpay;
	}
	
	/**
	 *
	 * @param tinyint $directpay
	 */
	public function setDirectPay($directpay)
	{
		$this->directpay = $directpay;
	}
	
	/**
	 *
	 */
	public function getNote()
	{
		return $this->note;
	}
	
	/**
	 *
	 * @param unknown $note
	 */
	public function setNote($note)
	{
		$this->note = $note;
	}
	
	/**
	 *
	 */
	public function getPrestations()
	{
		return $this->prestations;
	}
	
	/**
	 *
	 * @param unknown $prestations
	 */
	public function setPrestations($prestations)
	{
		$this->prestations = $prestations;
	}
	
	/**
	 * Return active
	 */
	public function getActive()
	{
		return $this->active;
	}
	
	/**
	 *
	 * @param tinyint $active
	 */
	public function setActive($active)
	{
		$this->active = $active;
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
		$this->note = 0;
        $this->prestations = 0;
        $this->directpay = 0;
		$this->active 	 = 1;
		$this->createdAt = new \DateTime('now');
		$this->updatedAt = new \DateTime('now');
		//Ajout du status
	}
}