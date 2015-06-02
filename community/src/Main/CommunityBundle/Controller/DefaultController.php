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
		//Find company
		//Appel du service d'inscription
		$serviceCompany = $this->get('service_company');
		$company = $serviceCompany->getCompany($usr->getId());
		
		return $this->render('MainCommunityBundle:Default:headBandDisplay.html.twig', array(
				'role'    => $roles[0],
				'company' => $company
		));
		
	}
}
