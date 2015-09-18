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
		$roles = $usr->getRoles();
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
		if ($this->get('security.context')->isGranted('ROLE_PHOTOGRAPHER_VERIFIED')) {
    		$devisService 		= $this->get('service_devis');
			$prestationService  = $this->get('service_prestation');
			$radiusService		= $this->get('service_moves_radius');
			$transactionService	= $this->get('service_transaction');
			$serviceCompany     = $this->get('service_company');
			$company 		    = $serviceCompany->getCurrentCompany();
			if (!$company) {
				$prestations = null;
				$devis 		 = null;
				$moves 		 = null;
				$groupbydevis = null;
				$groupbyprest = null;
				$money 		  = null;
				$week 		  = null;
			}else {
				$prestations 	= $prestationService->countAllMyServices();
				$devis 		 	= $devisService->CountAllMyActiveDevis();
				$moves 		 	= $radiusService->getRadius($company);
				$groupbydevis 	= $devisService->groupMyDevis();
				$groupbyprest 	= $prestationService->groupMyPrestations();
				$money			= $transactionService->getTotalMoney($company);	
				$week			= $prestationService->getWeekPrestations();
			}		
			return $this->render('MainCommunityBundle:Default:index.html.twig',  array(
					'prestations'	=> $prestations,
					'devis'			=> $devis,
					'moves'			=> $moves,
					'groupByDevis'	=> json_encode($groupbydevis),
					'groupByPrest'	=> json_encode($groupbyprest),
					'money'			=> $money,
					'week'			=> $week
			));	
		}else {
			return $this->redirect($this->generateUrl('company'));
		}
		
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
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/faq", name="faq")
	 */
	public function faqAction(Request $request)
	{		
		
		return $this->render('MainCommunityBundle:Default:faq.html.twig',  array(
		));	
	}

	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/cgu", name="cgu")
	 */
	public function cguAction(Request $request)
	{		
		
		return $this->render('MainCommunityBundle:Default:cgu.html.twig',  array(
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

	/**
	 * [logoutAfterValidAction description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function logoutAfterValidAction(Request $request)
	{
		$this->get('security.context')->getToken()->setAuthenticated(false);
		$response = new Response();
		return $response;
	}

	
	
}
