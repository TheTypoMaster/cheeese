<?php 

namespace Main\CommonBundle\Services\Users;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Users\User;
use Main\CommonBundle\Entity\Users\Preference;
use Main\CommonBundle\Services\Session\ServiceSession;


class ServiceEmail 
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
		$this->repository = $this->em->getRepository('MainCommonBundle:Users\Preference');
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
	 * [createPreferences description]
	 * @param  User   $user [description]
	 * @return [type]       [description]
	 */
	protected function createPreferences(User $user) {
		$preference = new Preference();
		$preference->setUser($user);
		try{
			$this->em->persist($preference);
			$this->em->flush();
			return $preference;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
			return false;
		}
	}

	/**
	 * [getCurrentPreferences description]
	 * @return [type] [description]
	 */
	public function getCurrentPreferences()
	{
		$user = $this->getCurrentUser();
		$preferences = $this->repository->getUserPreferences($user->getId());
		if ($preferences == null) {
			return $this->createPreferences($user);
		}else {
			return $preferences;
		}
	}
	
	/**
	 * [getPreferences description]
	 * @param  User   $user [description]
	 * @return [type]       [description]
	 */
	public function getPreferences(User $user)
	{
		$preferences = $this->repository->getUserPreferences($user->getId());
		if ($preferences == null) {
			return $this->createPreferences($user);
		}else {
			return $preferences;
		}
	}

	/**
	 * [updatePreferences description]
	 * @param  Preference $preference [description]
	 * @param  [type]     $params     [description]
	 * @return [type]                 [description]
	 */
	public function updatePreferences(Preference $preference, $params)
	{
		$preference->setPrestation($params['prestation']);
		$preference->setNewsletter($params['newsletter']);
		$preference->setMessages($params['messages']);
		try{
			$this->em->flush();
			$this->session->successFlashMessage('flash.message.email.index');
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
			return false;
		}
	}
		
}