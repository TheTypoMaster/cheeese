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
		$serviceIban = $this->get('service_iban');
		$iban = $serviceIban->getIban();
		if($iban !== null) {
			return $this->redirect($this->generateUrl('iban'));
		}else {
			$form = $this->createForm('form_iban', null, array());
			
			$form->handleRequest($request);
			if($request->isMethod('POST'))
			{
				$params = $request->request->get('form_iban');
				if ($form->isValid())
				{
					$add = $serviceIban->create($params);
					if($add) {
						return $this->redirect($this->generateUrl('iban'));
					}
				}
			
			}
			return $this->render('MainCommunityBundle:Iban:new.html.twig', array(
					'form' 		=> $form->createView(),
			));
			
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
		$serviceIban = $this->get('service_iban');
		$iban = $serviceIban->getIban();
		if($iban === null ){
			return $this->redirect($this->generateUrl('iban_new'));
		}else {
			return $this->render('MainCommunityBundle:Iban:view.html.twig', array(
					'iban' => $iban
			));
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
		$serviceIban = $this->get('service_iban');
		$iban = $serviceIban->getIban();
		if(!$iban){
			return $this->redirect($this->generateUrl('iban_new'));
		}else 
		{
			$form = $this->createForm('form_iban', $iban, array());
			$form->handleRequest($request);
			if($request->isMethod('POST'))
			{
				$params = $request->request->get('form_iban');
				if($form->isValid()) {
					$update = $serviceIban->update($iban, $params);
					if($update){
						return $this->redirect($this->generateUrl('iban'));
					}
				}
			}			
			return $this->render('MainCommunityBundle:Iban:edit.html.twig', array(
					'form' 		=> $form->createView(),
					'iban'		=> $iban						
			));
		}
	}
}
