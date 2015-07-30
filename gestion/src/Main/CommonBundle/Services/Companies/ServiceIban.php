<?php 

namespace Main\CommonBundle\Services\Companies;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Companies\Iban;
use Main\CommonBundle\Services\Session\ServiceSession;

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

	private $session;

	private $logger;

	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, ServiceSession $service, LoggerInterface $logger)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Companies\Iban');
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
	 * 
	 * @param unknown $id
	 */
	public function getIban()
	{
		return $this->repository->getIban($this->getCurrentUser()->getId());
	}
	
	/**
	 * [getPhotographerIban description]
	 * @param  [type] $user [description]
	 * @return [type]       [description]
	 */
	public function getPhotographerIban($user)
	{
		return $this->repository->findOneBy(array(
			'photographer' => $user 
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
			$this->session->successFlashMessage('flash.message.iban.new');
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
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
			$this->session->successFlashMessage('flash.message.iban.edit');
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
			return false;
		}
	}
}