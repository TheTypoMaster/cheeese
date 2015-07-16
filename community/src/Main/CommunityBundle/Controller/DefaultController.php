<?php

namespace Main\CommunityBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;

class DefaultController extends Controller
{
	/**
	 * Affichage du bandeau
	 */
	public function headBandDisplayAction()
	{
		$company = null;
		$usr= $this->get('security.context')->getToken()->getUser();
		//Appel du service d'inscription
		$serviceCompany = $this->get('service_company');
		$company = $serviceCompany->getCompany($usr->getId());
		return $this->render('MainCommunityBundle:Default:headBandDisplay.html.twig', array(
				'company'		=> $company,
		));
		
	}

		/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/", name="dashboard")
	 */
	public function dashboardAction(Request $request)
	{		
		$devisService 		= $this->get('service_devis');
		$prestationService  = $this->get('service_prestation');
		$radiusService		= $this->get('service_moves_radius');
		$transactionService	= $this->get('service_transaction');
		$serviceCompany = $this->get('service_company');
		$company 		= $serviceCompany->getCurrentCompany();
		if (!$company) {
			$prestations = null;
			$devis 		 = null;
			$moves 		 = null;
			$groupbydevis = null;
			$groupbyprest = null;
			$money 		  = null;
		}else {
			$prestations 	= $prestationService->countAllMyServices();
			$devis 		 	= $devisService->CountAllMyActiveDevis();
			$moves 		 	= $radiusService->getRadius($company);
			$groupbydevis 	= $devisService->groupMyDevis();
			$groupbyprest 	= $prestationService->groupMyPrestations();
			$money			= $transactionService->getTotalMoney($company);	
		}		
		return $this->render('MainCommunityBundle:Default:index.html.twig',  array(
				'prestations'	=> $prestations,
				'devis'			=> $devis,
				'moves'			=> $moves,
				'groupByDevis'	=> json_encode($groupbydevis),
				'groupByPrest'	=> json_encode($groupbyprest),
				'money'			=> $money,
		));	
	}

		/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/help", name="help")
	 */
	public function helpAction(Request $request)
	{		
		
		return $this->render('MainCommunityBundle:Default:help.html.twig',  array(
		));	
	}


	/**
	 * [aboutAction description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 * @Route("/about", name="about")
	 */
	public function aboutAction(Request $request)
	{
		return $this->render('MainCommunityBundle:Default:about.html.twig',  array(
		));	
	}

	
	
}
