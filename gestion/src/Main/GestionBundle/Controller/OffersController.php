<?php

namespace Main\GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class OffersController extends Controller
{
    /**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/offers", name="offers")
	 */
	public function indexAction(Request $request)
	{		
		$service = $this->get('service_devis');
		$offers = $service->getAllDevis();
		return $this->render('MainGestionBundle:Offers:index.html.twig',array(
			'offers' => $offers
			));	
	}

	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/{id}", requirements={"id" = "\d+"}, name="devis_read")
	 */
	public function showAction($id)
	{
		$service = $this->get('service_devis');
		$devis = $service->fetchPublic($id);
		if(!$devis)
		{
			throw $this->createNotFoundException('Page inexistante');
			
		}
		$servicePrestation = $this->get('service_prestation');
		$prestations = $servicePrestation->getByDevis($devis->getId());
		$servicePrices = $this->get('service_prices');
		$serviceDevisBook = $this->get('service_devis_book');
		$prices = $servicePrices->getPrices($devis);
		$book = $serviceDevisBook->fetchBook($devis);
		return $this->render('MainGestionBundle:Offers\show:index.html.twig',array(
			'devis' 		=> $devis,
			'prices'	  	=> $prices,
			'photos'		=> $book,
			'services' 	  	=> $prestations
			));	
	}

	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/disable/{id}", requirements={"id" = "\d+"}, name="devis_edit")
	 */
	public function editAction($id)
	{
		$service = $this->get('service_devis');
		$devis = $service->fetchPublic($id);
		if(!$devis)
		{
			throw $this->createNotFoundException('Page inexistante');
		}
		$service->changeActive($devis, 0);
		return $this->redirect($this->generateUrl('devis_read', array(
			'id' => $id
			)));
	}
}
