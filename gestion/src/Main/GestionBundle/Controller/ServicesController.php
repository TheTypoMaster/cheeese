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
			return $this->render('MainGestionBundle:Services\show:index.html.twig', array(
					'prestation' 			=> $service,
					'messages'				=> $messages,
					'commentClient'			=> $notation_client,
					'commentPhotographer'	=> $notation_photographer
			));
		}
	}
}
