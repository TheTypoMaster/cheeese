<?php

namespace Main\CommunityBundle\Controller\Companies;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;

class IbanController extends Controller
{
	
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/iban/new", name="iban_new")
	 */
	public function createAction(Request $request)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		$roles = $usr->getRoles();
		//Find company
		$serviceCompany = $this->get('service_company');
		$company = $serviceCompany->getCompany($usr->getId());
		if($company === null ){
			return $this->redirect($this->generateUrl('company_new'));
		}else {
			
		}
	}
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/iban", name="iban")
	 */
	public function viewAction(Request $request)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		$roles = $usr->getRoles();
		//Find company
		$serviceCompany = $this->get('service_company');
		$company = $serviceCompany->getCompany($usr->getId());
		if($company === null ){
			return $this->redirect($this->generateUrl('company_new'));
		}else {
			
		}
	}
	
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/iban/edit", name="iban_edit")
	 */
	public function editAction(Request $request)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		$roles = $usr->getRoles();
		//Find company
		//Appel du service d'inscription
		$serviceCompany = $this->get('service_company');
		$company = $serviceCompany->getCompany($usr->getId());
		if($company === null ){
			return $this->redirect($this->generateUrl('company_new'));
		}else {
			
		}
	}
}
