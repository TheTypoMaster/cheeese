<?php 

namespace Main\CommonBundle\Services\Offers;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Photographers\MoveRadius;
use Main\CommonBundle\Entity\Companies\Company;

class ServiceAvailability 
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
	
	protected $securityContext;
	
	
	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Photographers\Availability');
		$this->securityContext = $securityContext;		
	}
	
	/**
	 * Get Current user
	 */
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	}
	
	/**
	 * Get all devis
	 */
	public function getDates(Company $company)
	{
		return $this->repository->getDates($company->getId());
	}
	
	/**
	 * 
	 * @param unknown $dates
	 */
	public function updateDates(Company $company, $dates) 
	{
		return $this->repository->updateDates($company->getId(), $dates);
	}
	
	/**
	 * 
	 * @param unknown $dates
	 * @return multitype:unknown
	 */
	public function prepareDates($dates)
	{

		$result = array();
		if ($dates !== null) {
			foreach($dates as $date)
			{
				$result[] = $date['day']->format('Y-m-d');
			}
		}
		return $result;
	}

	
	
}