<?php 

namespace Main\CommonBundle\Services\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Prestations\Prestation;
use Main\CommonBundle\Entity\Users\User;
use Main\CommonBundle\Entity\Photographers\Devis;
use Main\CommonBundle\Entity\Messages\Message;

class ServicePrestation
{
	const PRESTATION_ENCOURS 	= 1;
	const PHOTOGRAPHER_OK 		= 2;
	const PHOTOGRAPHER_KO		= 3;
	const CLIENT_KO				= 4;
	const PRESTATION_OK			= 5;
	const OLD_PRESTATION		= 6;
	const CLOSED_PRESTATION		= 7;
	
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
	
	/**
	 * 
	 * @param EntityManager $entityManager
	 * @param SecurityContext $securityContext
	 */
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Prestations\Prestation');
		$this->securityContext = $securityContext;
	}
	
	/**
	 *
	 */
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	}
	
	/**
	 * 
	 * @param unknown $devis
	 * @param unknown $town
	 * @param unknown $day
	 * @param unknown $address
	 * @param unknown $startTime
	 * @param unknown $message
	 * @return boolean
	 */
	public function create($devis, $town, $day, $address, $startTime, $message)
	{
		$start      = str_replace('/', '-', $day).' '.$startTime['hour'].':'.$startTime['minute'].':00';
		$devis 		= $this->em->getRepository('MainCommonBundle:Photographers\Devis')->findOneById($devis);
		$client 	= $this->getCurrentUser();
		$town 		= $this->em->getRepository('MainCommonBundle:Geo\Town')->findOneById($town);
		$price   	= $devis->getPrice();
		$duration   = $devis->getDuration();
		$address 	= $address;
		$startTime 	= new \DateTime($start);
		$status 	= $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::PRESTATION_ENCOURS);
		
		
		$prestation = new Prestation();
		$prestation->setDevis($devis);
		$prestation->setClient($client);
		$prestation->setTown($town);
		$prestation->setPrice($price);
		$prestation->setAddress($address);
		$prestation->setStartTime($startTime);
		$prestation->setDuration($duration);
		$prestation->setStatus($status);	
		try{
			$this->em->persist($prestation);
			$this->CreateFirstPrestationMessage($prestation, $client, $devis->getCompany()->getPhotographer(), 1, $message);
			$this->em->flush();
			return $prestation;
		}catch(\Exception $e){
			var_dump($e->getMessage());
			return false;
		}
	}
	
	/**
	 * 
	 */
	public function listPhotographerServices()
	{
		return $this->repository->findPhotographerServices($this->getCurrentUser()->getId());
	}

	/**
	 * List all services for a given photographer
	 */
	public function listAllServices($id)
	{
		return $this->repository->findPhotographerServices($id);
	}
	
	/**
	 * 
	 * @param unknown $client
	 */
	public function getCurrentClientPrestation()
	{
		return $this->repository->findBy(
				array('client' => $this->getCurrentUser()->getId() ),
				array('id' => 'DESC')
				);
	}
	
	/**
	 * 
	 * @param unknown $id
	 */
	public function getPrestationAsClient($id)
	{
		return $this->repository->findOneBy(array(
				'id'	=> $id,
				'client' => $this->getCurrentUser()->getId()
				));
	}
	
	/**
	 * 
	 * @param unknown $id
	 */
	public function getPrestationAsPhotographer($id)
	{
		return $this->repository->findPhotographerServiceById($this->getCurrentUser()->getId(), $id);
	}

	/*
	 * 
	 * @param unknown $id
	 */
	public function getPrestation($id)
	{
		return $this->repository->findOneById($id);
	}
	
	/**
	 * 
	 * @param unknown $category
	 */
	public function getByDevis($devis)
	{
		return $this->repository->findBy(
				array('devis' => $devis),
				array('id' => 'DESC')
		);
	}
	
	/**
	 * [getAllServices description]
	 * @return [type]
	 */
	public function getAllServices()
	{
		return $this->repository->findBy(array(), array('createdAt' => 'desc'));
	}
	
	/**
	 * Initialisation du 1er Message concernant la prestation
	 */
	protected function CreateFirstPrestationMessage(Prestation $prestation, User $sender, User $receiver, $type, $content)
	{
		$message = new Message();
		$message->setType($type);
		$message->setPrestation($prestation);
		$message->setSender($sender);
		$message->setReceiver($receiver);
		$message->setContent($content);
		try{
			$this->em->persist($message);
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
			return false;
		}
		
	}

	/**
	 * 
	 * @param unknown $id
	 * @param unknown $slug
	 * @return boolean
	 */
	public function updatePrestation ($id, $slug)
	{
		$prestation = $this->repository->findOneById($id);
		//TODO : verifier l'etat ancien
		switch ($slug)
		{
			case 2:
				if ($prestation->getStatus()->getId() == self::PRESTATION_ENCOURS)
					$status 	= $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::PHOTOGRAPHER_OK);
				break;
			case 3:
				//Cancel-photographer
				if ($prestation->getStatus()->getId() == self::PRESTATION_ENCOURS || $prestation->getStatus()->getId() == self::PHOTOGRAPHER_OK)
					$status 	= $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::PHOTOGRAPHER_KO);
				break;
			case 4:
				//Cancel-Client
				if ($prestation->getStatus()->getId() == self::PRESTATION_ENCOURS || $prestation->getStatus()->getId() == self::PHOTOGRAPHER_OK)
					$status 	= $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::CLIENT_KO);
				break;
			case 5:
				//Valide
				if ($prestation->getStatus()->getId() == self::PHOTOGRAPHER_OK)
					$status 	= $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::PRESTATION_OK);
				break;
			case 6:
				if ($prestation->getStatus()->getId() == self::PRESTATION_OK)
				{
					$status 	= $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::OLD_PRESTATION);
					//Initialisation de la notation
				}
				break;
			case 7:
				if ($prestation->getStatus()->getId() == self::OLD_PRESTATION)
					$status 	= $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::CLOSED_PRESTATION);
				break;
		}
		
		
		$prestation->setStatus($status);
		$prestation->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->persist($prestation);
			$this->em->flush();
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
			return false;
		}
	}
	
	/**
	 * 
	 * @param Prestation $prestation
	 * @return boolean
	 */
	public function isCommentAllowed(Prestation $prestation)
	{
		return $prestation->getStatus()->getId() == self::OLD_PRESTATION || $prestation->getStatus()->getId() == self::CLOSED_PRESTATION;
	}
}