<?php 

namespace Main\CommunityBundle\Controller\Offers;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Main\CommonBundle\Entity\Photographers\Devis;

class DevisController extends Controller
{
	//Create
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/new", name="devis_new")
	 */
	public function createAction(Request $request)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$form = $this->createForm('form_devis', new Devis(), array());
		$form->handleRequest($request);
		if($request->isMethod('POST'))
		{
			$params = $request->request->get('form_devis');
			if ($form->isValid())
			{
				$serviceDevis = $this->get('service_devis');
				$add = $serviceDevis->create($params);
				if($add) {
					return $this->redirect($this->generateUrl('devis'));
				}
			}
				
		}
		return $this->render('MainCommunityBundle:Offers:devis_new.html.twig', array(
				'form' 		=> $form->createView(),
		));
		
		
	}
	
	//List
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis", name="devis")
	 */
	public function viewAction(Request $request)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->read();
		return $this->render('MainCommunityBundle:Offers:devis_view.html.twig', array(
				'devis' => $devis
		));
		
	}
	
	//Read
	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/{id}", requirements={"id" = "\d+"}, name="devis_read")
	 */
	 public function readAction($id)
	 {
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->fetch($id);
		if(!$devis){
				return $this->redirect($this->generateUrl('devis'));
			}
		else
		{
			$servicePrestation = $this->get('service_prestation');
			$prestations = $servicePrestation->getByDevis($devis->getId());
			return $this->render('MainCommunityBundle:Offers:devis_read.html.twig', array(
					'devis' 	  => $devis,
					'services' 	  => $prestations
			));
		}
	}
	
	//Update
	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/edit/{id}", requirements={"id" = "\d+"}, name="devis_edit")
	 */
	public function editAction($id)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->fetch($id);
		if(!$devis){
			return $this->redirect($this->generateUrl('devis'));
		}else{
			$form = $this->createForm('form_devis', $devis, array());
			$request = $this->get('request');
			$form->handleRequest($request);
			if($request->isMethod('POST'))
			{
				$params = $request->request->get('form_devis');
				if ($form->isValid())
				{
					$edit = $serviceDevis->edit($devis, $params);
					if($edit) {
						return $this->redirect($this->generateUrl('devis'));
					}
				}
			
			}
			return $this->render('MainCommunityBundle:Offers:devis_edit.html.twig', array(
					'devis' => $devis,
					'form'  => $form->createView()	
			));
		}
	}
	
	
	
	//Disable
	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/disable/{id}", requirements={"id" = "\d+"}, name="devis_disable")
	 */
	public function disableAction($id)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->fetch($id);
		if(!$devis){
			return $this->redirect($this->generateUrl('devis'));
		}else{
			$disable = $serviceDevis->changeActive($devis, 0);
			if($disable)
			return $this->redirect($this->generateUrl('devis'));
		}
	}	
	
	//Enable
	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/enable/{id}", requirements={"id" = "\d+"}, name="devis_enable")
	 */
	 public function enableAction($id)
	 {
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->fetch($id);
		if(!$devis){
			return $this->redirect($this->generateUrl('devis'));
		}else{
			$enable = $serviceDevis->changeActive($devis, 1);
			if($enable)
				return $this->redirect($this->generateUrl('devis'));
		}
	}
}
