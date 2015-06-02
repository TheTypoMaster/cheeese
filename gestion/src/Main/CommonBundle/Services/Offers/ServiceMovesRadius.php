<?php 

namespace Main\CommonBundle\Services\Offers;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Photographers\MoveRadius;
use Main\CommonBundle\Entity\Companies\Company;

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
	
	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Photographers\MoveRadius');
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
	public function getRadius()
	{
		return $this->repository->findOneBy(array('company' => $this->getCurrentUser()->getId()));
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
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
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
			$this->em->persist($radius);
			$this->em->flush();
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
			return false;
		}
	}
	
}