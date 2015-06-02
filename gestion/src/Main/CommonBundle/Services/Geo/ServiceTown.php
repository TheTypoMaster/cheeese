<?php 

namespace Main\CommonBundle\Services\Geo;
use Doctrine\ORM\EntityManager;

class ServiceTown 
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
		$this->repository = $this->em->getRepository('MainCommonBundle:Geo\Town');
	}	
	
	/**
	 * 
	 * @param unknown $country
	 * @param unknown $data
	 */
	public function findByCountry($country, $data)
	{
		return $this->repository->findByCountry($country, $data);
		
	}
}