<?php 

namespace Main\CommonBundle\Services\Messages;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Messages\Notification;
use Main\CommonBundle\Entity\Prestations\Prestation;
use Main\CommonBundle\Entity\Users\User;
use Main\CommonBundle\Services\Session\ServiceSession;


class ServiceNotification
{
	const PRESTATION_CREEE 		= 1;
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

	private $session;

	private $logger;

	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, ServiceSession $service, LoggerInterface $logger)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Messages\Notification');
		$this->securityContext = $securityContext;
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
	 * @param unknown $prestation
	 */
	public function readNotifications($prestation)
	{
		$notifications = $this->repository->getUnreadPrestationNotifications($this->getCurrentUser()->getId(), $prestation);	
		if(count($notifications) > 0)
		{
			//Update et rendre a 1
			foreach($notifications as $notification)
			{
				$notification->setRead(1);
				$notification->setUpdatedAt(new \DateTime('now'));
			}
			$this->em->flush();
		}
		
	}

	public function createPrestationNotification(Prestation $prestation)
	{
		$status = $prestation->getStatus()->getId();
        $photographer = $prestation->getDevis()->getCompany()->getPhotographer();
        $client = $prestation->getClient();
        switch ($status)
        {
            case 1:
                $to = $photographer;
                $subject = self::PRESTATION_CREEE;
                break;
            case 2:
                //PHOTOGRAPHER_OK
                $to = $client;
                $subject = self::PHOTOGRAPHER_OK;
                break;
            case 3:
            //Cancel-photographer
                $to = $client;
                $subject = self::PHOTOGRAPHER_KO;
                break;
            case 4:
            //Cancel-Client
                $to = $photographer;
                $subject = self::CLIENT_KO;
                break;
            case 5:
            //Valide
                $to = $photographer;
                $subject = self::PRESTATION_OK;
                break;
            case 6:
                $to = $photographer;
                $subject = self::OLD_PRESTATION;
                break;
            case 7:
                $to = $photographer;
                $subject = self::PHOTOS_DELIVERED;
                break;
            case 8:
                $to = $photographer;
                $subject = self::CLOSED_PRESTATION;
                break;
            case 9:
                $to = $client;
                $subject = self::CANCELED_PHOTOGRAPHER;
                break;
            case 10:
                $to = $photographer;
                $subject = self::CANCELED_CLIENT;
                break;
            case 11:
                $to = $photographer;
                $subject = self::LITIGE_CLIENT;
                break;
            case 12:
                $to = $client;
                $subject = self::LITIGE_PHOTOGRAPHER;
                break;
            

        }
        $notification = new Notification();
        $notification->setType(1);
        $notification->setPrestation($prestation);
        $notification->setContent($subject);
        $notification->setReceiver($to);
        try{
        	$this->em->persist($notification);
        	$this->em->flush();
        }catch(\Exception $e)
        {
        	$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
        }
	}
	
}