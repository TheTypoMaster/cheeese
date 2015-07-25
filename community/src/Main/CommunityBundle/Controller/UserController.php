<?php

namespace Main\CommunityBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{

	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/presentation", name="presentation")
	 */
	public function presentationAction(Request $request)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		return $this->render('MainCommunityBundle:Users:presentation.html.twig', array(
			'user' => $usr
			));
	}

	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/presentation/edit", name="presentation_edit")
	 */
	public function editPresentationAction(Request $request)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		$form = $this->createForm('form_presentation', $usr, array());
		$form->handleRequest($request);
		if($request->isMethod('POST'))
		{
			$params = $request->request->get('form_presentation');
			if ($form->isValid())
			{
				$serviceUser = $this->get('service_user');
				$edit = $serviceUser->updateUser($usr, $params);
				if($edit){
					return $this->redirect($this->generateUrl('presentation'));
				}
				
			}
				
		}
		return $this->render('MainCommunityBundle:Users:presentation_form.html.twig', array(
			'form' => $form->createView()
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
//		$hash = $this->get('security.password_encoder')->encodePassword($usr, 'test');
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
					return $this->redirect($this->generateUrl('presentation'));
				}
				
			}
				
		}
		return $this->render('MainCommunityBundle:Users:security.html.twig', array(
			'form' => $form->createView()
			));
	}

	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/presentation/pp", name="presentation_pp")
	 */
	public function profilPictureAction(Request $request)
	{
		$form = $this->createForm('form_devis_book', null, array());
		$request = $this->get('request');
		$form->handleRequest($request);
		if($request->isMethod('POST'))
			{
				$params = $request->request->get('form_devis_book');
				if ($form->isValid())
				{
					$serviceUser = $this->get('service_user');
					$edit = $serviceUser->editPP($this->get('security.context')->getToken()->getUser(), $form->getData());					
					if($edit) {
							return $this->redirect($this->generateUrl('presentation'));
					}
				}
			
			}
		return $this->render('MainCommunityBundle:Users:presentation_pp.html.twig', array(
				'form'  => $form->createView(),
		));

	}

	/**
	 * [preferencesAction description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 * @Route("/preferences", name="preferences")
	 */
	public function preferencesAction(Request $request)
	{
		$servicePreference = $this->get('service_preference_emails');
		$preference = $servicePreference->getCurrentPreferences();
		$form = $this->createForm('form_email', $preference, array());
		$form->handleRequest($request);
		if($request->isMethod('POST'))
		{
			$params = $request->request->get('form_email');
			if ($form->isValid())
			{
				$edit = $servicePreference->updatePreferences($preference, $params);
				if($edit){
					return $this->redirect($this->generateUrl('preferences'));
				}
				
			}
				
		}
		return $this->render('MainCommunityBundle:Users:email.html.twig', array(
			'form' => $form->createView()
			));
	}


}