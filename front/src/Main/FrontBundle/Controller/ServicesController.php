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
			$messageService = $this->get('service_message');
			$messageService->readMessages($id);
			$messages = $messageService->getPrestationMessages($id);
			
			if(!$prestationService->isCommentAllowed($service))
			{
				$allowed = false;
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
			else{
				//Cas ou le client peut ajouter/voir son evaluation
				$allowed = true;
				$notationService = $this->get('service_notation');
				$commentClient = $notationService->findByPrestation($id);
				$commentPhotographer = $notationService->findByPrestation($id, $service->getDevis()->getCompany()->getPhotographer()->getId()); 
			}
			
			return $this->render('MainFrontBundle:Prestations:show.html.twig', array(
					'prestation' 			=> $service,
					'messages'	 			=> $messages,
					'commentAllowed' 		=> $allowed,
					'commentClient'	 		=> $commentClient,
					'commentPhotographer'	=> $commentPhotographer,
					'form'		 			=> $formView
			));
		}
	}
	
	/**
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/service/{id}/update/{slug}", name="update_service")
	 */
	public function updateService($id, $slug)
	{
		$prestationService = $this->get('service_prestation');
		$service = $prestationService->getPrestationAsClient($id);
		if(!$service) {
			throw $this->createNotFoundException('The service does not exist');
		}
		else
		{
			if (!in_array($slug, array(4, 5)))
			{
				throw $this->createNotFoundException('Invalid slug');
			}else{
				$prestationService->updatePrestation($id, $slug);
				return $this->redirect($this->generateUrl('show_service', array('id' => $id)));
			}
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
			if($notation)
			{
				//Edit case
				$form = $this->createForm('form_evaluation', $notation, array());
				$request = $this->get('request');
				$form->handleRequest($request);
				if($request->isMethod('POST'))
				{
					$params = $request->request->get('form_evaluation');
					if ($form->isValid())
					{
						$notationService->editEvaluation($notation, $params['user_notation'], $params['user_comment'], $params['prestation_notation'], $params['prestation_comment']);
						return $this->redirect($this->generateUrl('show_service', array('id' => $id)));
					}
				}
				return $this->render('MainFrontBundle:Prestations:notation_edit.html.twig', array(
						'form'		 		=> $form->createView()
				));
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
						'form'		 		=> $form->createView()
				));
			}
		}
	}
}