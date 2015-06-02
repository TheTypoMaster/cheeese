<?php 

namespace Main\CommonBundle\Services\Messages;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Messages\Message;
use Main\CommonBundle\Entity\Prestations\Prestation;
use Main\CommonBundle\Entity\Users\User;

class ServiceMessage
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
		$this->repository = $this->em->getRepository('MainCommonBundle:Messages\Message');
		$this->securityContext = $securityContext;
	}
	
	/**
	 * 
	 * @param unknown $prestation
	 */
	public function getPrestationMessages($prestation)
	{
		return $this->repository->findBy(
				array('prestation' => $prestation ),
				array('id' => 'ASC')
				);
	}
	
	/**
	 * 
	 * @param unknown $prestation
	 * @param unknown $content
	 */
	public function createMessagePrestation($prestation, $content)
	{
		$prestation 	= $this->em->getRepository('MainCommonBundle:Prestations\Prestation')->findOneById($prestation);
		$sender 		= $this->getCurrentUser();
		if($this->getCurrentUser()->getId() == $prestation->getDevis()->getCompany()->getPhotographer()->getId())
		{
			$receiver		= $prestation->getClient();
		}elseif($this->getCurrentUser()->getId() == $prestation->getClient()->getId())
		{
			$receiver		= $prestation->getDevis()->getCompany()->getPhotographer();
		}
		return $this->createMessage($prestation, $sender, $receiver, 1, $content);
	}
	
	/**
	 * 
	 * @param Prestation $prestation
	 * @param User $sender
	 * @param User $receiver
	 * @param unknown $type
	 * @param unknown $content
	 * @return boolean
	 */
	protected function createMessage(Prestation $prestation = null, User $sender, User $receiver, $type, $content)
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
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
			return false;
		}
		
	}
	
	/**
	 * 
	 * @param unknown $prestation
	 */
	public function readMessages($prestation)
	{
		$messages = $this->repository->findBy(array(
				'prestation' => $prestation,
				'receiver'	 => $this->getCurrentUser()->getId(),
				'read'		 => 0
				));
		
		if(count($messages) > 0)
		{
			//Update et rendre a 1
			foreach($messages as $message)
			{
				$message->setRead(1);
				$message->setUpdatedAt(new \DateTime('now'));
				$this->em->persist($message);				
			}
			$this->em->flush();
		}
		
	}
	/**
	 *
	 */
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	}
	
}