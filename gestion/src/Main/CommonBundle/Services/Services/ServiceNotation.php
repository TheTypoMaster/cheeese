<?php 

namespace Main\CommonBundle\Services\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Prestations\Prestation as Prestation;
use Main\CommonBundle\Entity\Users\User as User;
use Main\CommonBundle\Entity\Photographers\Devis as Devis;
use Main\CommonBundle\Entity\Prestations\Evaluation as Evaluation;
use Main\CommonBundle\Entity\Notation\ClientNotation as ClientNotation;
use Main\CommonBundle\Services\Session\ServiceSession;
use Main\CommonBundle\Services\Offers\ServiceDevis;
use Main\CommonBundle\Services\Users\ServiceUser;

class ServiceNotation
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

	private $devis;

	private $user;
	
	/**
	 *
	 * @param EntityManager $entityManager
	 * @param SecurityContext $securityContext
	 */
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, ServiceSession $service, ServiceDevis $serviceDevis, ServiceUser $serviceUser)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Prestations\Evaluation');
		$this->securityContext = $securityContext;
		$this->session = $service;
		$this->devis = $serviceDevis;
		$this->user = $serviceUser;
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
	 * @param unknown $type
	 */
	public function findByPrestation($prestation, $type = null)
	{
		if($type === null) {
			$type = $this->getCurrentUser()->getId();
		}
		return $this->repository->findOneBy(array(
				'prestation' => $prestation,
				'scorer'	 => $type
				));
	}
	
	
	/**
	 * 
	 * @param Prestation $prestation
	 * @param unknown $serviceNote
	 * @param unknown $serviceComment
	 * @param unknown $photographerNote
	 * @param unknown $photographerComment
	 * @return boolean
	 */
	public function addEvaluation (Prestation $prestation, $userNote, $userComment,$serviceNote = null, $serviceComment =null)
	{
		
		$notation = new Evaluation();
		//$notation = new ClientNotation();
		$notation->setScorer($this->getCurrentUser());
		//$notation->setClient($this->getCurrentUser());
		$notation->setPrestation($prestation);
		$notation->setPrestationNotation($serviceNote);
		$notation->setPrestationComment($serviceComment);
		$notation->setUserNotation($userNote);
		//$notation->setPhotographerNotation($userNote);
		$notation->setUserComment($userComment);
		//$notation->setPhotographerComment($userComment);
		try{
			$this->em->persist($notation);
			$this->em->flush();
			$this->updateNotations($notation);
			$this->session->successFlashMessage('flash.message.notation.create');
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			var_dump($e->getMessage());
			die;
			return false;
		}
	}
	
	/**
	 * 
	 * @param ClientNotation $notation
	 * @param unknown $serviceNote
	 * @param unknown $serviceComment
	 * @param unknown $photographerNote
	 * @param unknown $photographerComment
	 * @return boolean
	 */
	public function editEvaluation(Evaluation $notation, $userNote, $userComment, $serviceNote = null, $serviceComment = null)
	{
		$notation->setPrestationNotation($serviceNote);
		$notation->setPrestationComment($serviceComment);
		$notation->setUserNotation($userNote);
		$notation->setUserComment($userComment);
		$notation->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->persist($notation);
			$this->em->flush();
			$this->updateNotations($notation);
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
			return false;
		}
	}

	private function updateNotations(Evaluation $evaluation)
	{
		$photographer = $evaluation->getPrestation()->getDevis()->getCompany()->getPhotographer()->getId();
        $client = $evaluation->getPrestation()->getClient()->getId();
        if($evaluation->getScorer()->getId() == $photographer)
        {
			$this->user->updateNotation($client, 'client'); 
        }
        elseif($evaluation->getScorer()->getId() == $client)
        {

        	$devis = $evaluation->getPrestation()->getDevis()->getId();
        	$this->devis->updateNotation($devis);
			$this->user->updateNotation($photographer, 'photographer');

        } 
	}
	
}