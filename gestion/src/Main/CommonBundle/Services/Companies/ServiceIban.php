<?php 

namespace Main\CommonBundle\Services\Companies;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Companies\Iban;

class ServiceIban
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
	
	private $securityContext;

	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Companies\Iban');
		$this->securityContext = $securityContext;
	}	
	
	/**
	 * Get Current user
	 */
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	}
	
	/**
	 * 
	 * @param unknown $id
	 */
	public function getIban()
	{
		return $this->repository->findOneBy(array(
			'photographer' => $this->getCurrentUser()->getId() 
		));
	}
	
	/**
	 * 
	 * @param unknown $data
	 * @return boolean
	 */
	public function create($data)
	{
		$user = $this->getCurrentUser();
		$Iban = new Iban();
		$Iban->setPhotographer($user);
		$Iban->setName($data['name']);
		$Iban->setAddress($data['address']);
		$Iban->setIban($data['iban']);
		$Iban->setBic($data['bic']);
		try{
			$this->em->persist($Iban);
			$this->em->flush();
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
			return false;
		}
	}
	
	/**
	 * 
	 * @param Iban $iban
	 */
	public function update(Iban $Iban, $data)
	{
		$Iban->setName($data['name']);
		$Iban->setAddress($data['address']);
		$Iban->setIban($data['iban']);
		$Iban->setBic($data['bic']);
		$Iban->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->flush();
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
			return false;
		}
	}
}