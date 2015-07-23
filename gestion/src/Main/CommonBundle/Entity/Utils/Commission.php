<?php

namespace Main\CommonBundle\Entity\Utils;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="utils.commission")
 * @ORM\Entity(repositoryClass="Main\CommonBundle\Entity\Utils\CommissionRepository")
 */
class Commission
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
	 * @var float $price
	 *
	 * @ORM\Column(name="customer", type="float", nullable=false, columnDefinition="FLOAT")
	 */
	private $customer;

	/**
	 * @var float $price
	 *
	 * @ORM\Column(name="photographer", type="float", nullable=false, columnDefinition="FLOAT")
	 */
	private $photographer;

	/**
	 * @var float $price
	 *
	 * @ORM\Column(name="premium", type="float", nullable=false, columnDefinition="FLOAT")
	 */
	private $premium;
	
	
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
	 * Return type
	 */
	public function getName()
	{
		return $this->name;
	}
	
	/**
	 *
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}
	
	/**
	 * Return icon
	 */
	public function getCustomer()
	{
		return $this->customer;
	}
	
	/**
	 *
	 * @param string $icon
	 */
	public function setCustomer($customer)
	{
		$this->customer = $customer;
	}
	
	/**
	 * Return active
	 */
	public function getPhotographer()
	{
		return $this->photographer;
	}
	
	/**
	 *
	 * @param tinyint $active
	 */
	public function setPhotographer($photographer)
	{
		$this->photographer = $photographer;
	}

	/**
	 * Return active
	 */
	public function getPremium()
	{
		return $this->premium;
	}
	
	/**
	 *
	 * @param tinyint $active
	 */
	public function setPremium($premium)
	{
		$this->premium = $premium;
	}


}