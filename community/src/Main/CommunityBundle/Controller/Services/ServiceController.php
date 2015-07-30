<?php 

namespace Main\CommunityBundle\Controller\Services;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Main\CommonBundle\Entity\Photographers\Devis;
use Main\CommonBundle\Entity\Prestations\Evaluation;

class ServiceController extends Controller
{	
	//List
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/service", name="service")
	 */
	public function listAction(Request $request)
	{
		$servicePrestation = $this->get('service_prestation');
		$prestations = $servicePrestation->listPhotographerServices();
		return $this->render('MainCommunityBundle:Services:index.html.twig', array(
				'services' => $prestations
		));
		
	}
	
	
	//Show
	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/service/{id}", requirements={"id" = "\d+"}, name="service_show")
	 */
	public function showAction($id)
	{
		$prestationService = $this->get('service_prestation');
		$service = $prestationService->getPrestationAsPhotographer($id);
		if(!$service) {
			throw $this->createNotFoundException('The service does not exist');
		}
		else
		{
			$messageService = $this->get('service_message');
			$messageNotification= $this->get('service_notification');
			$ibanService = $this->get('service_iban');
			$commentClient  = null;
			$commentPhotographer = null;
			$formView = null;
			$allowed = false;
			$passed = $prestationService->isPassed($service);			
			$messageService->readMessages($id);
			$messageNotification->readNotifications($id);
			$messages = $messageService->getPrestationMessages($id);
			$iban = $ibanService->getIban();
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
							return $this->redirect($this->generateUrl('service_show', array('id' => $id)));
						}
					}
				}
			}
			if($passed)
			{
				//Cas ou le client peut ajouter/voir son evaluation
				$notationService = $this->get('service_notation');
				$commentClient = $notationService->findByPrestation($id, $service->getClient()->getId());
				$commentPhotographer = $notationService->findByPrestation($id);
			}
			
			return $this->render('MainCommunityBundle:Services\show:index.html.twig', array(
					'prestation' 			=> $service,
					'messages'				=> $messages,
					'commentAllowed' 		=> $allowed,
					'passed'				=> $passed,
					'commentClient'	 		=> $commentClient,
					'commentPhotographer'	=> $commentPhotographer,
					'form'		 			=> $formView,
					'iban'					=> $iban
			));
		}
	}

 	/**
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/service/{id}/refuse", requirements={"id" = "\d+"}, name="service_refuse")
	 */
	public function refuseAction($id)
	{
		$prestationService = $this->get('service_prestation');
		$service = $prestationService->getPrestationAsPhotographer($id);
		if(!$service) {
			throw $this->createNotFoundException('The service does not exist');
		}
		else
		{
			$prestationService->refusePrestation($service);
			return $this->redirect($this->generateUrl('service_show', array('id' => $id)));
		}
	}

	/**
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/service/{id}/pre-approve", requirements={"id" = "\d+"}, name="service_pre_approve")
	 */
	public function PreApprovePrestation($id)
	{
		$prestationService = $this->get('service_prestation');
		$service = $prestationService->getPrestationAsPhotographer($id);
		if(!$service) {
			throw $this->createNotFoundException('The service does not exist');
		}
		else
		{
			$prestationService->PreApprovePrestation($service);
			return $this->redirect($this->generateUrl('service_show', array('id' => $id)));
		}
	}
	
	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/service/{id}/update/{slug}", requirements={"id" = "\d+"}, name="service_edit")
	 */
	public function editAction($id, $slug)
	{
		$prestationService = $this->get('service_prestation');
		$service = $prestationService->getPrestationAsPhotographer($id);
		if(!$service) {
			throw $this->createNotFoundException('The service does not exist');
		}
		else
		{
			if ($service->getStatus()->getId() != 1 || !in_array($slug, array('price', 'duration', 'date')))
			{
				return $this->redirect($this->generateUrl('service_show', array('id' => $id)));
			}else{
				$form = $this->createForm('form_prestation', null, array(
					'devis' 	=> $service->getDevis()->getId(),
					'slug'		=> $slug,
					'date'		=> $service->getStartTime(),
					'price' 	=> $service->getPrice(),
					'duration' 	=> $service->getDuration()
					));
				$request = $this->get('request');
				$form->handleRequest($request);
				if($request->isMethod('POST'))
				{
					$params = $request->request->get('form_prestation');
					if ($form->isValid())
					{
						$edit = $prestationService->updateAsPhotographer($params, $service, $slug);
						if($edit) 
							{
							return $this->redirect($this->generateUrl('service_show', array('id' => $id)));
							}
					}
					
				}
				return $this->render('MainCommunityBundle:Services:edit.html.twig', array(
						'form'	=> $form->createView(),
						'slug'	=> $slug,
						'prestation' => $service
						));
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
		$service = $prestationService->getPrestationAsPhotographer($id);
		if(!$service) {
			throw $this->createNotFoundException('The service does not exist');
		}
		else
		{
			$notationService = $this->get('service_notation');
			$notation = $notationService->findByPrestation($id);
			if($notation || !$prestationService->isPassed($service))
			{
				return $this->redirect($this->generateUrl('service_show', array('id' => $id)));
				
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
						$notationService->addEvaluation($service, $params['user_notation'], $params['user_comment']);
						return $this->redirect($this->generateUrl('service_show', array('id' => $id)));
					}
				}
			}
			return $this->render('MainCommunityBundle:Services:notation.html.twig', array(
					'form'		 		=> $form->createView()
			));
		}
	}
}
