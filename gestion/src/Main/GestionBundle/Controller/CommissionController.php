<?php

namespace Main\GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class CommissionController extends Controller
{
    /**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/commission", name="commission")
	 */
	public function commissionAction(Request $request)
	{	
		$serviceCommission = $this->get('service_commission');
		$commissions = $serviceCommission->getAll();	
		return $this->render('MainGestionBundle:Commission:index.html.twig', array(
			'commissions' => $commissions
			));	
	}

	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/commission/particuliers", name="commission_particuliers")
	 */
	public function editCommissionPartAction(Request $request)
	{
		$serviceCommission = $this->get('service_commission');
		$commission = $serviceCommission->getCommissionParticulier();
		$form = $this->createForm('form_commission', $commission);
		$form->handleRequest($request);
		if($request->isMethod('POST'))
			{
				$params = $request->request->get('form_commission');
				if ($form->isValid())
				{
					$edit = $serviceCommission->updateCommission($commission, $params);
					return $this->redirect($this->generateUrl('commission'));
				}
			}
		return $this->render('MainGestionBundle:Commission:edit.html.twig', array(
				'commission' => $commission,
				'form'		=> $form->createView()
			));
		
	}

	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/commission/entreprises", name="commission_entreprises")
	 */
	public function editCommissionEntAction(Request $request)
	{
		$serviceCommission = $this->get('service_commission');
		$commission = $serviceCommission->getCommissionEntreprise();
		$form = $this->createForm('form_commission', $commission);
		$form->handleRequest($request);
		if($request->isMethod('POST'))
			{
				$params = $request->request->get('form_commission');
				if ($form->isValid())
				{
					$edit = $serviceCommission->updateCommission($commission, $params);
					return $this->redirect($this->generateUrl('commission'));
				}
			}
		return $this->render('MainGestionBundle:Commission:edit.html.twig', array(
				'commission' => $commission,
				'form'		=> $form->createView()
			));
		
	}

	
}
