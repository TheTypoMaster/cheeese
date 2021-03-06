<?php 

namespace Main\CommonBundle\Services\Offers;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Photographers\MoveRadius;
use Main\CommonBundle\Entity\Companies\Company;
use Main\CommonBundle\Services\Session\ServiceSession;


class ServiceMovesRadius 
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

	private $logger;
	
	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, ServiceSession $service, LoggerInterface $logger)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Photographers\MoveRadius');
		$this->securityContext = $securityContext;
		$this->session = $service;
		$this->logger = $logger;
		
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
	public function getRadius(Company $company)
	{
		return $this->repository->getRadius($company->getId());
	}
	
	/**
	 * 
	 * @param unknown $data
	 * @return boolean
	 */
	public function create($data, Company $company)
	{
		$radius = new MoveRadius();
		$radius->setRadius($data['radius']);
		$radius->setCompany($company);
		try{
			$this->em->persist($radius);
			$this->em->flush();
			$this->session->successFlashMessage('flash.message.moves.create');
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
			return false;
		}
	}
	
	/**
	 * 
	 */
	 public function edit(MoveRadius $radius, $data)
	{
		$radius->setRadius($data['radius']);
		$radius->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->flush();
			$this->session->successFlashMessage('flash.message.moves.edit');
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
			return false;
		}
	}	
}