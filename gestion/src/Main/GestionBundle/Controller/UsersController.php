<?php

namespace Main\GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends Controller
{
    /**
	 * * @param string type
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/users/{type}", name="users")
	 */
	public function indexAction($type)
	{	
		$serviceUser = $this->get('service_user');
		if($type === 'administrators') {
			$users = $serviceUser->getUsersByRole('ROLE_ADMIN');
			return $this->render('MainGestionBundle:Users\Administrators:index.html.twig', array(
				'users' => $users
				));	
		}	
		elseif($type === 'photographers') {
			$users = $serviceUser->getUsersByRole('ROLE_PHOTOGRAPHER_VERIFIED');
			$others = $serviceUser->getUsersByRole('ROLE_PHOTOGRAPHER');
			$serviceCompany = $this->get('service_company');
			$verify = $serviceCompany->getCountUnverifiedCompanies();
			return $this->render('MainGestionBundle:Users\Photographers:index.html.twig', array(
				'users' 	=> $users,
				'others'	=> $others,
				'verify'	=>	$verify
				));
		}elseif($type === 'individuals') {
			$users = $serviceUser->getUsersByRole('ROLE_PARTICULIER');			
			return $this->render('MainGestionBundle:Users\Individuals:index.html.twig', array(
				'users' => $users,
				));
		}else{
			throw $this->createNotFoundException('Page inexistante');
		}
	}

	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/photographers/verify", name="verify-photographers")
	 */
	public function verifyListPhotographersAction(Request $request)
	{
			$serviceCompany = $this->get('service_company');
			$users  = $serviceCompany->getPhotographersToVerify();
			return $this->render('MainGestionBundle:Users\Photographers\verify:index.html.twig', array(
				'users' 	=> $users,
				));
	}


	/**
	*
	* @Route(
	*			"/photographer/{id}/{result}", 
	*			name="verify-photographer",
	*			requirements={"result" = "[0-1]"}
	*		)
	*/
	public function verifyPhotographer($id, $result)
	{
		$serviceCompany = $this->get('service_company');
		$company = $serviceCompany->getCompany($id);
		if ($company) {
			$serviceCompany->verifyPhotographer($company, $result);
			return $this->redirect($this->generateUrl('verify-photographers'));
		}else {
			throw $this->createNotFoundException('Page inexistante');
		}
	}

	/**
	 * [showuser description]
	 * @param  [type]
	 * @return [type]
	 * @Route("/user/{id}", requirements={"id" = "\d+"}, name="user_show")
	 */
	public function showuser($id)
	{
		$serviceUser = $this->get('service_user');
		$user = $serviceUser->getUser($id);
		if(!$user) {
			throw $this->createNotFoundException('Page inexistante');	
		}
		$roles = $user->getRoles();
		if(in_array('ROLE_ADMIN', $roles)) {
			return $this->render('MainGestionBundle:Users\Administrators\show:index.html.twig', array(
				'user' 	=> $user,
				'company'	=> null
				));
		}
		elseif(in_array('ROLE_PARTICULIER', $roles)) {
			$services = null;
			$servicePrestations = $this->get('service_prestation');
			$services = $servicePrestations->listAllClientServices($id);
			return $this->render('MainGestionBundle:Users\Individuals\show:index.html.twig', array(
				'user' 		=> $user,
				'services'	=> $services,
				'company'	=> null
				));
		}
		elseif(in_array('ROLE_PHOTOGRAPHER_VERIFIED', $roles) || in_array('ROLE_PHOTOGRAPHER', $roles)) {
			$devis 		= null;
			$services 	= null;
			$company 	= null;
			$verified 	= false;
			$serviceCompany = $this->get('service_company');
			$company = $serviceCompany->getCompany($id);
			if ($company) {
				if ($serviceCompany->isVerifiedCompany($company)) {
					$serviceDevis  = $this->get('service_devis');
					$devis = $serviceDevis->getAllByCompany($company->getId());
					$servicePrestations = $this->get('service_prestation');
					$services = $servicePrestations->listAllServices($id);
					$verified = $serviceCompany->isVerifiedCompany($company);
				}
			}
			

			
			return $this->render('MainGestionBundle:Users\Photographers\show:index.html.twig', array(
				'user' 			=> $user,
				'company'		=> $company,
				'verified'		=> $verified,
				'offers'		=> $devis,
				'services'		=> $services
				));
		}
	}

	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/create-user", name="create_user")
	 */
	public function createAdminAction(Request $request)
	{
		$form = $this->createForm('form_create_admin', null, array());
		$form->handleRequest($request);
		if($request->isMethod('POST'))
		{
			$params = $request->request->get('form_create_admin');
			if ($form->isValid())
			{
				$serviceUser = $this->get('service_user');
				$create = $serviceUser->createUser($params);
				if($create){
					return $this->redirect($this->generateUrl('users', array(
							'type' => 'administrators'
						)));
				}
				
			}
				
		}
			return $this->render('MainGestionBundle:Users\Administrators\show:new.html.twig', array(
				'form' 	=> $form->createView(),
				));
	}

	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/disable-user/{id}", name="disable_user")
	 */
	public function disableAdminAction($id)
	{
		$serviceUser = $this->get('service_user');
		$user = $serviceUser->getUser($id);
		if(!$user) {
			return $this->redirect($this->generateUrl('users', array(
							'type' => 'administrators'
						)));
		}
		$serviceUser->disableUser($user);
		return $this->redirect($this->generateUrl('user_show', array(
							'id' => $id
						)));
	}

	/**
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/suspend-company/{id}", name="suspend_company")
	 */
	public function suspendCompanyAction($id)
	{
		$serviceCompany = $this->get('service_company');
		$company = $serviceCompany->getCompany($id);
		if (!$company) {
			return $this->redirect($this->generateUrl('users', array(
							'type' => 'photographers'
						)));
		}
		$serviceCompany->suspendCompany($company);
		return $this->redirect($this->generateUrl('user_show', array(
							'id' => $id
						)));
	}

	/**     
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/resume-company/{id}", name="resume_company")
	 */
	public function resumeCompanyAction($id)
	{
		$serviceCompany = $this->get('service_company');
		$company = $serviceCompany->getCompany($id);
		if (!$company) {
			return $this->redirect($this->generateUrl('users', array(
							'type' => 'photographers'
						)));
		}
		$serviceCompany->resumeCompany($company);
		return $this->redirect($this->generateUrl('user_show', array(
							'id' => $id
						)));
	}
}
