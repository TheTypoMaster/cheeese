<?php 

namespace Main\CommunityBundle\Controller\Offers;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;

class AvailabilityController extends Controller
{
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/availability", name="availability")
	 */
	public function indexAction(Request $request)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		$serviceCompany = $this->get('service_company');
		$serviceAvailability = $this->get('service_availability');
		$company = $serviceCompany->getCompany($usr->getId());		
		$dates = $serviceAvailability->getDates($company);
		$dates = $serviceAvailability->prepareDates($dates);
		return $this->render('MainCommunityBundle:Offers:availability.html.twig', array(
				'company' 	=> $company->getPhotographer()->getId(),
				'dates'		=> $dates
		));
		
		
	}
	
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/submit-availability", name="availability_submit")
	 */
	public function submitAction(Request $request)
	{
		if($request->isMethod('POST'))
		{
			$params = $request->request->all();
			$serviceAvailability = $this->get('service_availability');
			if (count($params) == 0) {
				$serviceAvailability->errorDates(); 
			}
			else {
				$dates = $params['dates'];
				$serviceCompany = $this->get('service_company');
				$company = $serviceCompany->getCompany($this->get('security.context')->getToken()->getUser()->getId());
				$update = $serviceAvailability->updateDates($company, $dates); 
				if($update) {
					return $this->redirect($this->generateUrl('availability'));
				}
			}
			
		}
		return $this->redirect($this->generateUrl('availability'));
		
		
		
	
	}
}
