<?php

namespace Main\GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ServicesController extends Controller
{
    /**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/services", name="services")
	 */
	public function indexAction(Request $request)
	{		
		$servicePrestation = $this->get('service_prestation');
		$services = $servicePrestation->getAllServices();
		return $this->render('MainGestionBundle:Services:index.html.twig', array(
			'services' => $services
			));	
	}

	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/service/{id}", requirements={"id" = "\d+"}, name="service_show")
	 */
	public function showAction($id)
	{
		$prestationService = $this->get('service_prestation');
		$service = $prestationService->getPrestation($id);
		if(!$service) {
			throw $this->createNotFoundException('The service does not exist');
		}
		else
		{
			$messageService = $this->get('service_message');
			$messages = $messageService->getPrestationMessages($id); 
			$notationService = $this->get('service_notation');	
			$notation_client = $notationService->findByPrestation($id, $service->getClient()->getId());
			$notation_photographer = $notationService->findByPrestation($id, $service->getDevis()->getCompany()->getPhotographer()->getId());
			$transaction = null;
			$closed = false;
			$delivered = $prestationService->isDelivered($service);
			$editCommissionCustomer = $prestationService->canEditCommissionCustomer($service);
			$editCommissionPhotographer = $prestationService->canEditCommissionPhotographer($service);
			/*
			if($prestationService->isDelivered($service)) {
				$delivered = true;
			}
			*/
			if($prestationService->isClosed($service)) {
				$transactionService = $this->get('service_transaction');
				$closed = true;
				$transaction = $transactionService->get($service);
			}
			return $this->render('MainGestionBundle:Services\show:index.html.twig', array(
					'prestation' 			=> $service,
					'messages'				=> $messages,
					'commentClient'			=> $notation_client,
					'commentPhotographer'	=> $notation_photographer,
					'transaction'			=> $transaction,
					'delivered'				=> $delivered,
					'closed'				=> $closed,
					'editCommCust'			=> $editCommissionCustomer,
					'editCommPhot'			=> $editCommissionPhotographer
			));
		}
	}

	/**
	 * [editCommissionAction description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 * @Route("/service/{id}/commission/{type}", requirements={"id" = "\d+"}, name="service_commission")
	 */
	public function editCommissionAction($id, $type)
	{
		$prestationService = $this->get('service_prestation');
		$service = $prestationService->getPrestation($id);
		if(!$service) {
			throw $this->createNotFoundException('The service does not exist');
		}
		else
		{
			if ($type != 1 && $type != 2)
			{
				throw $this->createNotFoundException('The service does not exist');
			}else{
				$form = $this->createForm('form_commission_prestation', $service->getCommission(), array(
					'type' => $type
					));
				$request = $this->get('request');
				$form->handleRequest($request);
				if($request->isMethod('POST'))
				{
					$params = $request->request->get('form_commission_prestation');
					if ($form->isValid())
					{
						$serviceCommission = $this->get('service_prestation_commission');
						$edit = $serviceCommission->editCommission($service->getCommission(), $type, $params);
						if($edit) {
							return $this->redirect($this->generateUrl('service_show', array(
								'id' => $service->getId()
								))
							);
						}
					}
				}
				return $this->render('MainGestionBundle:Services\show:commission.html.twig', array(
						'prestation' => $service,
						'form'		=> $form->createView(),
						'type'		=> $type
				));

			}
		}
	}

	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/service/{id}/pay", requirements={"id" = "\d+"}, name="service_pay")
	 */
	public function payServiceAction($id)
	{
		$prestationService = $this->get('service_prestation');
		$service = $prestationService->getPrestation($id);
		if(!$service) {
			throw $this->createNotFoundException('The service does not exist');
		}
		else
		{
			$ibanService = $this->get('service_iban');
			$iban = $ibanService->getPhotographerIban($service->getDevis()->getCompany()->getPhotographer()->getId());
			$commission = ($service->getPrice() * $service->getCommission()->getPhotographer())/100;
			$price = $service->getPrice() - $commission;
			$form = $this->createForm('form_transaction', null, array());
			$request = $this->get('request');
			$form->handleRequest($request);
			if($request->isMethod('POST'))
			{
				$params = $request->request->get('form_transaction');
				if ($form->isValid())
				{
					$serviceTransaction = $this->get('service_transaction');
					$add = $serviceTransaction->create($params['comments'], $price, $iban, $service);
					if($add) {
						return $this->redirect($this->generateUrl('service_show', array(
							'id' => $service->getId()
							))
						);
					}
				}
			}
			return $this->render('MainGestionBundle:Services\show:pay.html.twig', array(
					'prestation' => $service,
					'iban'		 => $iban,
					'price'		 => $price,
					'form'		=> $form->createView()
			));
		}
	}
}
