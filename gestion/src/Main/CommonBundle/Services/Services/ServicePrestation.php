<?php 

namespace Main\CommonBundle\Services\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Prestations\Prestation;
use Main\CommonBundle\Entity\Users\User;
use Main\CommonBundle\Entity\Photographers\Devis;
use Main\CommonBundle\Entity\Messages\Message;
use Main\CommonBundle\Services\Services\ServiceReference;
use Main\CommonBundle\Services\Emails\ServiceEmail;
use Main\CommonBundle\Services\Session\ServiceSession;



class ServicePrestation
{
	const PRESTATION_ENCOURS 	= 1;
	const PHOTOGRAPHER_OK 		= 2;
	const PHOTOGRAPHER_KO		= 3;
	const CLIENT_KO				= 4;
	const PRESTATION_OK			= 5;
	const OLD_PRESTATION		= 6;
	const PHOTOS_DELIVERED		= 7;
	const CLOSED_PRESTATION		= 8;

	
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

	private $mailer;
	
	private $reference;

	private $session;

	private $logger;

	/**
	 * 
	 * @param EntityManager $entityManager
	 * @param SecurityContext $securityContext
	 */
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, ServiceReference $reference, ServiceEmail $mailer,  ServiceSession $service, LoggerInterface $logger)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Prestations\Prestation');
		$this->securityContext = $securityContext;
		$this->reference = $reference;
		$this->mailer = $mailer;
		$this->session = $service;
		$this->logger = $logger;
	}
	
	/**
	 *
	 */
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
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
	 * List all services for a given photographer
	 */
	public function listAllClientServices($id)
	{
		return $this->repository->findBy(
				array('client' => $id ),
				array('id' => 'DESC')
				);
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
	 * [PreApprovePrestation description]
	 * @param Prestation $prestation [description]
	 */
	public function PreApprovePrestation(Prestation $prestation)
	{
		return $this->updatePrestation($prestation, self::PHOTOGRAPHER_OK);
	}

	/**
	 * [refusePrestation description]
	 * @param  Prestation $prestation [description]
	 * @return [type]                 [description]
	 */
	public function refusePrestation(Prestation $prestation)
	{
		return $this->updatePrestation($prestation, self::PHOTOGRAPHER_KO);
	}

	/**
	 * [cancelPrestation description]
	 * @param  Prestation $prestation [description]
	 * @return [type]                 [description]
	 */
	public function cancelPrestation(Prestation $prestation)
	{
		return $this->updatePrestation($prestation, self::CLIENT_KO);
	}

	/**
	 * [confirmPrestation description]
	 * @param  Prestation $prestation [description]
	 * @return [type]                 [description]
	 */
	public function confirmPrestation(Prestation $prestation)
	{
		return $this->updatePrestation($prestation, self::PRESTATION_OK);
	}

	/**
	 * [PassPrestation description]
	 * @param Prestation $prestation [description]
	 */
	public function PassPrestation(Prestation $prestation)
	{
		return $this->updatePrestation($prestation, self::PHOTOS_DELIVERED);
	}

	/**
	 * [closePrestation description]
	 * @param  Prestation $prestation [description]
	 * @return [type]                 [description]
	 */
	public function closePrestation(Prestation $prestation)
	{
		return $this->updatePrestation($prestation, self::CLOSED_PRESTATION);
	}
	/**
	 * [isClosed description]
	 * @param  Prestation $prestation [description]
	 * @return boolean                [description]
	 */
	public function isClosed(Prestation $prestation)
	{
		return $prestation->getStatus()->getId() == self::CLOSED_PRESTATION;
	}

	public function isDelivered(Prestation $prestation)
	{
		return $prestation->getStatus()->getId() == self::PHOTOS_DELIVERED;
	}

	/**
	 * [isOld description]
	 * @param  Prestation $prestation [description]
	 * @return boolean                [description]
	 */
	public function isOld(Prestation $prestation)
	{
		return $prestation->getStatus()->getId() == self::OLD_PRESTATION;
	}

	/**
	 * Update les prestations confirmées passées en old
	 */
	public function setPassedPrestations()
	{
		$date = new \DateTime('now');
		$this->repository->setPassedPrestations($date, self::PRESTATION_OK, self::OLD_PRESTATION);
	}
	
	/**
	 * 
	 * @param Prestation $prestation
	 * @return boolean
	 */
	public function isCommentAllowed(Prestation $prestation)
	{
		return $prestation->getStatus()->getId() == self::PRESTATION_ENCOURS || $prestation->getStatus()->getId() == self::PHOTOGRAPHER_OK || $prestation->getStatus()->getId() == self::PRESTATION_OK;
	}

	/**
	 * [isPassed description]
	 * @param  Prestation $prestation [description]
	 * @return boolean                [description]
	 */
	public function isPassed(Prestation $prestation)
	{
		return $prestation->getStatus()->getId() > self::PRESTATION_OK;
	}

	public function countAllMyServices()
	{
		return $this->countAll($this->getCurrentUser()->getId());
	}

	/**
	 * [countAll description]
	 * @return [type] [description]
	 */
	public function countAll($user = null)
	{
		return $this->repository->countAll($user);
	}

	/**
	 * [groupMyPrestations description]
	 * @return [type] [description]
	 */
	public function groupMyPrestations()
	{
		return $this->groupBy($this->getCurrentUser()->getId());
	}
	/**
	 * [groupBy description]
	 * @param  [type] $user [description]
	 * @return [type]       [description]
	 */
	public function groupBy($user = null) 
	{
		return $this->repository->groupBy($user);
	}

	public function getWeekPrestations()
	{
		$next = date("Y-m-d",strtotime("+1 week"));
		$today = date("Y-m-d",strtotime("today"));
		return $this->repository->getWeekPrestations($today, $next, self::PRESTATION_OK, $this->getCurrentUser()->getId());
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
	public function create($devis, $town, $day, $address, $startTime, $duration, $message)
	{
		$start      = str_replace('/', '-', $day).' '.$startTime['hour'].':'.$startTime['minute'].':00';
		$devis 		= $this->em->getRepository('MainCommonBundle:Photographers\Devis')->findOneById($devis);
		$client 	= $this->getCurrentUser();
		$town 		= $this->em->getRepository('MainCommonBundle:Geo\Town')->findOneById($town);
		$devisPrice = $this->em->getRepository('MainCommonBundle:Photographers\DevisPrices')->findOneBy(array(
			'devis' => $devis,
			'duration' => $duration));
		$duration   = $devisPrice->getDuration();
		$price 		= $devisPrice->getPrice();
		$address 	= $address;
		$startTime 	= new \DateTime($start);
		$status 	= $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::PRESTATION_ENCOURS);
		
		
		$prestation = new Prestation();
		$prestation->setReference($this->reference->generateReference());
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
			$this->mailer->prestationUpdateEmail($prestation);
			return $prestation;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
			return false;
		}
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
			$this->logger->error($e->getMessage());
			return false;
		}
		
	}

	/**
	 * 
	 * @param unknown $id
	 * @param unknown $slug
	 * @return boolean
	 */
	public function updatePrestation (Prestation $prestation, $slug)
	{
		switch ($slug)
		{
			case 2:
				if ($prestation->getStatus()->getId() == self::PRESTATION_ENCOURS)
					{
					$status 	= $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::PHOTOGRAPHER_OK);
					$flashMessage = 'flash.message.prestation.status.pre_approved';	
					}					
				break;
			case 3:
				//Cancel-photographer
				if ($prestation->getStatus()->getId() == self::PRESTATION_ENCOURS || $prestation->getStatus()->getId() == self::PHOTOGRAPHER_OK)
					{
						$status 	= $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::PHOTOGRAPHER_KO);
						$flashMessage = 'flash.message.prestation.status.index';
					}					
				break;
			case 4:
				//Cancel-Client
				if ($prestation->getStatus()->getId() == self::PRESTATION_ENCOURS || $prestation->getStatus()->getId() == self::PHOTOGRAPHER_OK)
					{
						$status 	= $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::CLIENT_KO);
						$flashMessage = 'flash.message.prestation.status.index';
					}					
				break;
			case 5:
				//Valide
				if ($prestation->getStatus()->getId() == self::PHOTOGRAPHER_OK)
				{
					$status 	= $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::PRESTATION_OK);
					$flashMessage = 'flash.message.prestation.status.index';
				}					
				break;
			case 6:
				if ($prestation->getStatus()->getId() == self::PRESTATION_OK)
				{
					$status 	= $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::OLD_PRESTATION);
					$flashMessage = 'flash.message.prestation.status.index';
				}
				break;
			case 7:
				if ($prestation->getStatus()->getId() == self::OLD_PRESTATION)
				{
					$status 	= $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::PHOTOS_DELIVERED);
					$flashMessage = 'flash.message.prestation.status.index';
				}
				break;
			case 8:
				if ($prestation->getStatus()->getId() == self::PHOTOS_DELIVERED)
				{
					$status 	= $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::CLOSED_PRESTATION);
					$flashMessage = 'flash.message.prestation.status.index';
				}
				break;
		}
		
		
		$prestation->setStatus($status);
		$prestation->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->flush();
			//Envoi du mail
            $this->mailer->prestationUpdateEmail($prestation);
			$this->session->successFlashMessage($flashMessage);	
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
			return false;
		}
	}


}