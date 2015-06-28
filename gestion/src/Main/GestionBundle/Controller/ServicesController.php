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
			$delivered = false;
			if($prestationService->isDelivered($service)) {
				$delivered = true;
			}
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
					'closed'				=> $closed
			));
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
			$commission = ($service->getPrice() * $this->container->getParameter('commission_particulier'))/100;
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
