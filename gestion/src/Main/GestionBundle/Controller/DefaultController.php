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
		return $this->render('MainGestionBundle:Default:index.html.twig');	
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
