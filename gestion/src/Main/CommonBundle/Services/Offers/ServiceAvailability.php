<?php 

namespace Main\CommonBundle\Services\Offers;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Services\Session\ServiceSession;
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

	private $session;
	
	
	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, ServiceSession $service)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Photographers\Availability');
		$this->securityContext = $securityContext;	
		$this->session = $service;	
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
		
		try{
			$this->session->successFlashMessage('flash.message.availability.index');
			return $this->repository->updateDates($company->getId(), $dates);
		}catch(\Exception $e)
		{
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
		}
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

	/**
	 * [errorDates description]
	 * @return [type] [description]
	 */
	public function errorDates()
	{
		$this->session->errorFlashMessageCustom('flash.message.availability.error');
	}

	/**
	 * [findDatesByCompany description]
	 * @param  [type] $company [description]
	 * @return [type]          [description]
	 */
	public function findDatesByCompany($company)
	{
		$dates = $this->repository->getDates($company);
		$results = array();
		if(count($dates) > 0) {
			foreach ($dates as $date) {
				if ($date["day"] instanceof \DateTime) {
	  			$results[] = $date["day"]->format('d-m-Y');
				}				
			}
		}		
		return $results;
	}

	/**
	 * [isDateAvailable description]
	 * @param  [type]  $arg   [description]
	 * @param  [type]  $dates [description]
	 * @return boolean        [description]
	 */
	public function isDateAvailable($arg, $dates) {
		$date = str_replace('/', '-', $arg);
		$result = null;
		if(in_array($date, $dates)) {
			$result = $date;
		}
		return $result;
	}

	/**
	 * [removeOldDates description]
	 * @return [type] [description]
	 */
	public function removeOldDates()
	{
		$date = new \DateTime('now'); 
		$this->repository->removeOldDates($date->format('Y-m-d'));
	}	
}