<?php

namespace Main\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Main\CommonBundle\Entity\Prestations\Evaluation;

class ServicesController extends Controller
{
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/services", name="user_services")
	 */
	public function indexPublicAction(Request $request)
	{
		$services = null;
		$prestationService = $this->get('service_prestation');
		$services = $prestationService->getCurrentClientPrestation();
		return $this->render('MainFrontBundle:Prestations:index.html.twig', array(
						'prestations' => $services
		));
	}
	
	/**
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/service/{id}", name="show_service")
	 */
	public function showServiceAction($id)
	{
		$prestationService = $this->get('service_prestation');
		$service = $prestationService->getPrestationAsClient($id);
		if(!$service) {
			throw $this->createNotFoundException('The service does not exist');
		}
		else 
		{
			$commentClient  = null;
			$commentPhotographer = null;
			$formView = null;
			$allowed = false;
			$treated = false;
			$passed = $prestationService->isPassed($service);
			$messageService = $this->get('service_message');
			$messageService->readMessages($id);
			$messages = $messageService->getPrestationMessages($id);
			
			if($prestationService->isCommentAllowed($service))
			{
				$allowed = true;
				$form = $this->createForm('form_message', null, array());
				$formView = $form->createView();
				$request = $this->get('request');
				$form->handleRequest($request);
				if($request->isMethod('POST'))
				{
					$params = $request->request->get('form_message');
					if ($form->isValid())
					{
						$add = $messageService->createMessagePrestation($id, $params['content']);
						if($add) {
							return $this->redirect($this->generateUrl('show_service', array('id' => $id)));
						}
					}
				}
			}
			if($passed){
				$treated = $prestationService->isOld($service);
				$notationService = $this->get('service_notation');
				$commentClient = $notationService->findByPrestation($id);
				$commentPhotographer = $notationService->findByPrestation($id, $service->getDevis()->getCompany()->getPhotographer()->getId()); 
			}
			
			return $this->render('MainFrontBundle:Prestations\\show:index.html.twig', array(
					'prestation' 			=> $service,
					'messages'	 			=> $messages,
					'commentAllowed' 		=> $allowed,
					'passed'				=> $passed,
					'treated'				=> $treated,
					'commentClient'	 		=> $commentClient,
					'commentPhotographer'	=> $commentPhotographer,
					'form'		 			=> $formView
			));
		}
	}

	/**
	 * [cancelService description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 * @Route("/service/{id}/cancel", name="cancel_service")
	 */
	public function cancelServiceAction($id)
	{
		$prestationService = $this->get('service_prestation');
		$service = $prestationService->getPrestationAsClient($id);
		if(!$service) {
			throw $this->createNotFoundException('The service does not exist');
		}
		else{
			$prestationService->cancelPrestation($service);
			return $this->redirect($this->generateUrl('show_service', array('id' => $id)));
		}
	}

	/**
	 * [confirmService description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 * @Route("/service/{id}/confirm", name="confirm_service")
	 */
	public function confirmServiceAction($id)
	{
		$prestationService = $this->get('service_prestation');
		$service = $prestationService->getPrestationAsClient($id);
		if(!$service) {
			throw $this->createNotFoundException('The service does not exist');
		}
		else{
			$prestationService->confirmPrestation($service);
			return $this->redirect($this->generateUrl('show_service', array('id' => $id)));
		}
	}

	/**
	 * [passService description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 * @Route("/service/{id}/deliver_service", name="deliver_service")
	 */
	public function passServiceAction($id)
	{
		$prestationService = $this->get('service_prestation');
		$service = $prestationService->getPrestationAsClient($id);
		if(!$service) {
			throw $this->createNotFoundException('The service does not exist');
		}
		else{
			$prestationService->passPrestation($service);
			return $this->redirect($this->generateUrl('show_service', array('id' => $id)));
		}
	}
	

	/**
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/service/{id}/notation", name="notation")
	 */
	public function notationAction($id)
	{
		$prestationService = $this->get('service_prestation');
		$service = $prestationService->getPrestationAsClient($id);
		if(!$service) {
			throw $this->createNotFoundException('The service does not exist');
		}
		else
		{
			$notationService = $this->get('service_notation');
			$notation = $notationService->findByPrestation($id);
			if($notation || !$prestationService->isPassed($service))
			{
				return $this->redirect($this->generateUrl('show_service', array('id' => $id)));
			}else {
				//Create case
				$form = $this->createForm('form_evaluation', new Evaluation(), array());
				$request = $this->get('request');
				$form->handleRequest($request);
				if($request->isMethod('POST'))
				{
					$params = $request->request->get('form_evaluation');
					if ($form->isValid())
					{
						$notationService->addEvaluation($service, $params['user_notation'], $params['user_comment'], $params['prestation_notation'], $params['prestation_comment']);
						return $this->redirect($this->generateUrl('show_service', array('id' => $id)));
					}
				}
				return $this->render('MainFrontBundle:Prestations:notation_create.html.twig', array(
						'form'		 		=> $form->createView(),
						'prestation'		=> $service
				));
			}
		}
	}
}