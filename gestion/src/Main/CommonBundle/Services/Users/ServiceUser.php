<?php 

namespace Main\CommonBundle\Services\Users;

use Doctrine\ORM\EntityManager;
use Main\CommonBundle\Entity\Users\Photographer;

class ServiceUser 
{
	/**
	 *
	 * @var EntityManager
	 */
	private $em;
	
	/**
	 * 
	 * @var string
	 */
	private $repository;
	
	public function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Users\User');
	}	
	
	
}