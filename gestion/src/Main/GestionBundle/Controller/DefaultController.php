<?php

namespace Main\GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
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
		$companyService  	= $this->get('service_company');
		$prestations  = $prestationService->countAll();
		$devis 		  = $devisService->countAllActive();
		$verify 	  = $companyService->countAllBy(1);
		$verified 	  = $companyService->countAllBy(2);
		$groupbydevis = $devisService->groupBy();
		$groupbyprest = $prestationService->groupBy();

		return $this->render('MainGestionBundle:Default:index.html.twig', array(
			'prestations' 	=> $prestations,
			'devis'			=> $devis,
			'verify'		=> $verify,
			'verified'		=> $verified,
			'groupByDevis'	=> json_encode($groupbydevis),
			'groupByPrest'	=> json_encode($groupbyprest),			
			));	
	}

	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/security", name="security")
	 */
	public function securityAction(Request $request)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		$form = $this->createForm('form_security', null, array());
		$form->handleRequest($request);
		if($request->isMethod('POST'))
		{
			$params = $request->request->get('form_security');
			if ($form->isValid())
			{

				$serviceUser = $this->get('service_user');
				$edit = $serviceUser->updatePassword($usr, $params);
				if($edit){
					return $this->redirect($this->generateUrl('user_show', array(
							'id' => $usr->getId()
						)));
				}
				
			}
				
		}
		return $this->render('MainGestionBundle:Users:security.html.twig', array(
			'form' => $form->createView()
			));
	}
}
