<?php 

namespace Main\CommonBundle\Services\Transactions;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Companies\Iban;
use Main\CommonBundle\Entity\Prestations\Prestation;
use Main\CommonBundle\Entity\Companies\Company;
use Main\CommonBundle\Entity\Companies\Transaction;
use Main\CommonBundle\Services\Services\ServicePrestation;
use Main\CommonBundle\Services\Session\ServiceSession;


class ServiceTransaction 
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

	private $service;

	private $session;

	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, ServicePrestation $servicePrestation, ServiceSession $serviceSession)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Companies\Transaction');
		$this->securityContext = $securityContext;
		$this->service = $servicePrestation;
		$this->session = $serviceSession;
	}	

	/**
	 *
	 */
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	}

	/**
	 * [addPrice description]
	 * @param Devis  $devis    [description]
	 * @param [type] $duration [description]
	 * @param [type] $price    [description]
	 */
	public function create ($comment, $price, Iban $iban, Prestation $prestation) 
	{	
		
		$transaction = new Transaction();
		$transaction->setPhotographer($prestation->getDevis()->getCompany()->getPhotographer());
		$transaction->setPrestation($prestation);
		$transaction->setPrice($price);
		$transaction->setIban($iban->getIban());
		$transaction->setComment($comment);	
		try{
			$this->em->persist($transaction);
			$this->em->flush();
			$this->service->closePrestation($prestation);
			$this->session->successFlashMessage('flash.message.transaction.create');
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			var_dump($e->getMessage());
			return false;
		}
		
	}
	
	/**
	 * [get description]
	 * @param  Prestation $prestation [description]
	 * @return [type]                 [description]
	 */
	public function get(Prestation $prestation)
	{
		return $this->repository->findOneByPrestation($prestation->getId());
	}

	/**
	 * [getByUser description]
	 * @return [type] [description]
	 */
	public function getByUser()
	{
		//return $this->repository->findByPhotographer($this->getCurrentUser()->getId());
		return $this->repository->getTransactions($this->getCurrentUser()->getId());
	}

	public function getTotalMoney(Company $company)
	{
		return $this->repository->getTotalMoney($this->getCurrentUser()->getId());
	}
	
}