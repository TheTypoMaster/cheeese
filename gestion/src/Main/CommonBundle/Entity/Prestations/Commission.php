<?php

namespace Main\CommonBundle\Entity\Prestations;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="prestations.commission")
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
	 * @ORM\Column(name="customer", type="float", nullable=false, columnDefinition="FLOAT")
	 */
	private $customer;

	/**
	 * @ORM\Column(name="photographer", type="float", nullable=false, columnDefinition="FLOAT")
	 */
	private $photographer;
	
	/**
	 * @ORM\Column(name="comment", type="text", nullable=true)
	 */
	private $comment;
	

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
	 * [getCustomer description]
	 * @return [type] [description]
	 */
	public function getCustomer()
	{
		return $this->customer;
	}

	/**
	 * [setCustomer description]
	 * @param [type] $customer [description]
	 */
	public function setCustomer($customer)
	{
		$this->customer = $customer;
	}

	/**
	 * [getPhotographer description]
	 * @return [type] [description]
	 */
	public function getPhotographer()
	{
		return $this->photographer;
	}

	/**
	 * [setPhotographer description]
	 * @param [type] $photographer [description]
	 */
	public function setPhotographer($photographer)
	{
		$this->photographer = $photographer;
	}

	/**
	 *
	 */
	public function getComment()
	{
		return $this->comment;
	}
	
	/**
	 *
	 * @param text $prestation_comment
	 */
	public function setComment($comment)
	{
		$this->comment = $comment;
	}
}