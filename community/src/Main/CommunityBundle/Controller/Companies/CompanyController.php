<?php

namespace Main\CommunityBundle\Controller\Companies;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;

class CompanyController extends Controller
{
	
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/company/new", name="company_new")
	 */
	public function createAction(Request $request)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		//Find company
		$serviceCompany = $this->get('service_company');
		$company = $serviceCompany->getCompany($usr->getId());
		if($company !== null) {
			return $this->redirect($this->generateUrl('company'));
		}else {
			$form = $this->createForm('form_company', null, array());
			
			$form->handleRequest($request);
			if($request->isMethod('POST'))
			{
				$params = $request->request->get('form_company');
				if ($form->isValid())
				{
					$add = $serviceCompany->create($params);
					if($add) {
						return $this->redirect($this->generateUrl('company'));
					}
				}
			
			}
			return $this->render('MainCommunityBundle:Company:new.html.twig', array(
					'form' 		=> $form->createView(),
			));
			
		}
	}
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/company", name="company")
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
			return $this->render('MainCommunityBundle:Company:view.html.twig', array(
					'company' => $company
			));
		}
	}
	
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/company/edit", name="company_edit")
	 */
	public function editAction(Request $request)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		//Find company
		$serviceCompany = $this->get('service_company');
		$company = $serviceCompany->getCompany($usr->getId());
		if($company === null ){
			return $this->redirect($this->generateUrl('company_new'));
		}
		$form = $this->createForm('form_company', null, array(
				'status'  		 => $company->getStatus()->getId(),
				'title'	  		 => $company->getTitle(),
				'address'		 => $company->getAddress(),
				'town'	    	 => $company->getTown()->getName(),
				'country' 		 => $company->getTown()->getCountry()->getId(),
				'identification' => $company->getIdentification(),
				));
			
		$form->handleRequest($request);
		if($request->isMethod('POST'))
		{
			$params = $request->request->get('form_company');
			if($form->isValid()) {
				$update = $serviceCompany->update($company, $params);
				if($update){
					return $this->redirect($this->generateUrl('company'));
				}
			}
		}
			
		return $this->render('MainCommunityBundle:Company:edit.html.twig', array(
				'form' 		=> $form->createView(),
				'company'	=> $company						
		));
		
	}
}
