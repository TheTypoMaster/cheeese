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
			$serviceCompany = $this->get('service_company');
			$verify = $serviceCompany->getCountUnverifiedCompanies();
			return $this->render('MainGestionBundle:Users\Photographers:index.html.twig', array(
				'users' 	=> $users,
				'verify'	=>	$verify
				));
		}elseif($type === 'individuals') {
			$users = $serviceUser->getUsersByRole('ROLE_PARTICULIER');			
			return $this->render('MainGestionBundle:Users\Individuals:index.html.twig', array(
				'users' => $users
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
			return $this->render('MainGestionBundle:Users\Photographers:verify.html.twig', array(
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

}
