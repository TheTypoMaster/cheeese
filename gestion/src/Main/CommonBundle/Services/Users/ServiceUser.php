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

	/**
	*
	*
	*/
	public function getUsersByRole($role)
	{
		return $this->repository->getUsersByRole($role);
	}	

	/**
	 * [getUserByRole description]
	 * @param  [type]
	 * @return [type]
	 */
	public function getUser($id)
	{
		return $this->repository->findOneById($id);
	}
	
}