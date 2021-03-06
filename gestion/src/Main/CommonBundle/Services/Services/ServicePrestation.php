<?php 

namespace Main\CommonBundle\Services\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Prestations\Prestation;
use Main\CommonBundle\Entity\Users\User;
use Main\CommonBundle\Entity\Photographers\Devis;
use Main\CommonBundle\Entity\Messages\Message;
use Main\CommonBundle\Entity\Prestations\Commission as CommissionPrestation;
use Main\CommonBundle\Services\Services\ServiceReference;
use Main\CommonBundle\Services\Emails\ServiceEmail;
use Main\CommonBundle\Services\Session\ServiceSession;
use Main\CommonBundle\Services\Services\ServiceCommission;
use Main\CommonBundle\Services\Messages\ServiceNotification;

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
	const CANCELED_PHOTOGRAPHER	= 9;
	const CANCELED_CLIENT		= 10;
	const LITIGE_CLIENT			= 11;
	const LITIGE_PHOTOGRAPHER	= 12;

	
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

	private $serviceCommissionPrestation;

	private $notification;
	/**
	 * 
	 * @param EntityManager $entityManager
	 * @param SecurityContext $securityContext
	 */
	public function __construct(EntityManager $entityManager, 
								SecurityContext $securityContext, 
								ServiceReference $reference, 
								ServiceEmail $mailer, 
								ServiceSession $service, 
								LoggerInterface $logger, 								
								ServiceCommission $commissionServicePrestation,
								ServiceNotification $notificationService)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Prestations\Prestation');
		$this->securityContext = $securityContext;
		$this->reference = $reference;
		$this->mailer = $mailer;
		$this->session = $service;
		$this->logger = $logger;		
		$this->serviceCommissionPrestation = $commissionServicePrestation;
		$this->notification = $notificationService;
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
	 * [isConfirmed description]
	 * @param  Prestation $prestation [description]
	 * @return boolean                [description]
	 */
	public function isConfirmed(Prestation $prestation) {
		return $prestation->getStatus()->getId() == self::PRESTATION_OK;
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
	 * 
	 * @param Prestation $prestation
	 * @return boolean
	 */
	public function isCommentAllowed(Prestation $prestation)
	{
		return $prestation->getStatus()->getId() == self::PRESTATION_ENCOURS || $prestation->getStatus()->getId() == self::PHOTOGRAPHER_OK || $prestation->getStatus()->getId() == self::PRESTATION_OK;
	}

	/**
	 * [isTelephoneDisplayed description]
	 * @param  Prestation $prestation [description]
	 * @return boolean                [description]
	 */
	public function isTelephoneDisplayed(Prestation $prestation)
	{
		return 
		   $prestation->getStatus()->getId() == self::PRESTATION_OK
		|| $prestation->getStatus()->getId() == self::OLD_PRESTATION
	 	|| $prestation->getStatus()->getId() == self::PHOTOS_DELIVERED;
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

	/**
	 * Update les prestations confirmées passées en old
	 */
	public function setPassedPrestations()
	{
		$status = $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::OLD_PRESTATION);
		$prestations = $this->repository->getPassedPrestation(new \DateTime('now'), self::PRESTATION_OK);
		if (count($prestations) > 0 ){

			foreach ($prestations as $prestation) {
			$prestation->setStatus($status);
			$prestation->setUpdatedAt(new \DateTime('now'));
			$this->notification->createPrestationNotification($prestation);
			}
			try{
				$this->em->flush();
				//Envoi du mail
	            //$this->mailer->prestationUpdateEmail($prestation);
				return true;
			}catch(\Exception $e){
				$this->logger->error($e->getMessage());
				return false;
			}
		}		
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
	/**
	 * [getWeekPrestations description]
	 * @return [type] [description]
	 */
	public function getWeekPrestations()
	{
		$next = date("Y-m-d",strtotime("+1 week"));
		$today = date("Y-m-d",strtotime("today"));
		return $this->repository->getWeekPrestations($today, $next, self::PRESTATION_OK, $this->getCurrentUser()->getId());
	}

	/**
	 * [canEditCommissionCustomer description]
	 * @param  Prestation $prestation [description]
	 * @return [type]                 [description]
	 */
	public function canEditCommissionCustomer(Prestation $prestation) {
		return $prestation->getStatus()->getId() == self::PRESTATION_ENCOURS || $prestation->getStatus()->getId() == self::PHOTOGRAPHER_OK;
	}

	/**
	 * [canEditCommissionPhotographer description]
	 * @param  Prestation $prestation [description]
	 * @return [type]                 [description]
	 */
	public function canEditCommissionPhotographer(Prestation $prestation)
	{
		return  $prestation->getStatus()->getId() == self::PRESTATION_ENCOURS || 
				$prestation->getStatus()->getId() == self::PHOTOGRAPHER_OK || 
				$prestation->getStatus()->getId() == self::PRESTATION_OK || 
				$prestation->getStatus()->getId() == self::OLD_PRESTATION;

	}
	/**
	 * [setPrestationCanceled description]
	 * @param Prestation $prestation [description]
	 * @param [type]     $comments   [description]
	 * @param [type]     $author     [description]
	 */
	public function setPrestationCanceled(Prestation $prestation, $comments, $author) {
		if ($author == 1) {
			$status = $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::CANCELED_PHOTOGRAPHER);
			$flashMessage = 'flash.message.prestation.status.index';
			$sender = $prestation->getDevis()->getCompany()->getPhotographer();
			$receiver = $prestation->getClient();
		}elseif($author == 2) {
			$status = $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::CANCELED_CLIENT);
			$flashMessage = 'flash.message.prestation.status.index';
			$sender = $prestation->getClient();
			$receiver = $prestation->getDevis()->getCompany()->getPhotographer();
		}
		$prestation->setStatus($status);
		$prestation->setUpdatedAt(new \DateTime('now'));
		try{
			//Envoi du mail
			$this->CreateMessage($prestation, $sender, $receiver, 1, $comments);
			$this->em->flush();
			$this->notification->createPrestationNotification($prestation);
			$this->mailer->prestationUpdateEmail($prestation, $comments);
			$this->session->successFlashMessage($flashMessage);
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
			return false;
		}
	}

	/**
	 * [setPrestationLitige description]
	 * @param Prestation $prestation [description]
	 * @param [type]     $comments   [description]
	 * @param [type]     $author     [description]
	 */
	public function setPrestationLitige(Prestation $prestation, $author, $comments = null) {
		if ($author == 1) {
			$status = $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::LITIGE_PHOTOGRAPHER);
			$flashMessage = 'flash.message.prestation.status.index';
		}elseif($author == 2) {
			$status = $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::LITIGE_CLIENT);
			$flashMessage = 'flash.message.prestation.status.index';
		}
		$prestation->setStatus($status);
		$prestation->setUpdatedAt(new \DateTime('now'));
		try{
			//Envoi du mail
			$this->em->flush();
			$this->notification->createPrestationNotification($prestation);
			$this->mailer->prestationUpdateEmail($prestation, $comments);
			$this->session->successFlashMessage($flashMessage);
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
			return false;
		}
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
		$client 	= $this->getCurrentUser();
		$town 		= $this->em->getRepository('MainCommonBundle:Geo\Town')->findOneById($town);
		$address 	= $address;
		$status 	= $this->em->getRepository('MainCommonBundle:Status\PrestationStatus')->findOneById(self::PRESTATION_ENCOURS);

		$devisPrice = $this->em->getRepository('MainCommonBundle:Photographers\DevisPrices')->findOneBy(array(
			'devis' => $devis->getId(),
			'duration' => $duration));
		$duration   = $devisPrice->getDuration();
		$price 		= $devisPrice->getPrice();		
		$start      = str_replace('/', '-', $day).' '.$startTime.':00';
		$startTime 	= new \DateTime($start);

		$commission = $this->serviceCommissionPrestation->generateCommission($devis);		
		
		$prestation = new Prestation();
		$prestation->setReference($this->reference->generateReference());
		$prestation->setDevis($devis);
		$prestation->setRappel($devis->getPresentation());
		$prestation->setClient($client);
		$prestation->setTown($town);
		$prestation->setPrice($price);
		$prestation->setAddress($address);
		$prestation->setStartTime($startTime);
		$prestation->setDuration($duration);
		$prestation->setStatus($status);	
		$prestation->setCommission($commission);
		try{
			$this->em->persist($prestation);
			$this->CreateMessage($prestation, $client, $devis->getCompany()->getPhotographer(), 1, $message);
			$this->em->flush();
			$this->mailer->prestationUpdateEmail($prestation);
			$this->notification->createPrestationNotification($prestation);
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
	protected function CreateMessage(Prestation $prestation, User $sender, User $receiver, $type, $content)
	{
		$message = new Message();
		$message->setType($type);
		$message->setPrestation($prestation);
		$message->setSender($sender);
		$message->setReceiver($receiver);
		$message->setContent($content);
		try{
			$this->em->persist($message);
			$this->em->flush();
		}catch(\Exception $e){
			$this->logger->error($e->getMessage());
		}
		
	}

	public function updateAsPhotographer($params, Prestation $prestation, $slug)
	{
		if ($slug == 'price'){
			$prestation->setPrice($params['price']);
			$flashMessage = 'flash.message.prestation.edit.price';
		}elseif($slug == 'duration')
		{
			$devisPrice = $this->em->getRepository('MainCommonBundle:Photographers\DevisPrices')->findOneBy(array(
			'devis' => $prestation->getDevis()->getId(),
			'duration' => $params['duration']));
			$duration   = $devisPrice->getDuration();
			$flashMessage = 'flash.message.prestation.edit.duration';
			$prestation->setDuration($duration);
		}elseif($slug == 'date'){
			$start      = str_replace('/', '-', $params['date']).' '.$params['time'].':00';
			$startTime 	= new \DateTime($start);
			$flashMessage = 'flash.message.prestation.edit.date';
			$prestation->setStartTime($startTime);
		}
		$prestation->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->flush();
			//Envoi du mail
            //$this->notification->createPrestationNotification($prestation);
			$this->session->successFlashMessage($flashMessage);	
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
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
				if ($prestation->getStatus()->getId() == self::PHOTOS_DELIVERED || 
					$prestation->getStatus()->getId() == self::LITIGE_PHOTOGRAPHER ||
					$prestation->getStatus()->getId() == self::LITIGE_CLIENT 	||
					$prestation->getStatus()->getId() == self::CANCELED_CLIENT ||
					$prestation->getStatus()->getId() == self::CANCELED_PHOTOGRAPHER
				)
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
            $this->notification->createPrestationNotification($prestation);
			$this->session->successFlashMessage($flashMessage);	
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
			return false;
		}
	}


}