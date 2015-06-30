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
	
	/**
	 *
	 * @param EntityManager $entityManager
	 * @param SecurityContext $securityContext
	 */
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, ServiceSession $service)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Prestations\Evaluation');
		$this->securityContext = $securityContext;
		$this->session = $service;
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
			$this->session->successFlahMessage('flash.message.notation.create');
			return true;
		}catch(\Exception $e){
			$this->session->errorFlahMessage();
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
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
			return false;
		}
	}
	
}