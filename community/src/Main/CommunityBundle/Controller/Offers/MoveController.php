<?php 

namespace Main\CommunityBundle\Controller\Offers;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;

class MoveController extends Controller
{
	//Index
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/moves", name="moves")
	 */
	public function indexAction(Request $request)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$serviceCompany = $this->get('service_company');
		$serviceMoves = $this->get('service_moves_radius');
		$company = $serviceCompany->getCompany($usr->getId());		
		$radiusMove = $serviceMoves->getRadius($company);		
		if($radiusMove) {
			$radius = $radiusMove->getRadius();
		}else {
			$radius = 0;
		}
		$form = $this->createForm('form_moves', $radiusMove, array());
		$form->handleRequest($request);
		if($request->isMethod('POST'))
		{
			$params = $request->request->get('form_moves');
			if ($form->isValid()) {
				if($radius === 0) {
				$add = $serviceMoves->create($params, $company);
				if($add) {
					return $this->redirect($this->generateUrl('moves'));
				}
			}else {
				$edit = $serviceMoves->edit($radiusMove, $params);
				if($edit) {
					return $this->redirect($this->generateUrl('moves'));
				}
			}
		}			
			
		}
		return $this->render('MainCommunityBundle:Offers:moves.html.twig', array(
				'company'  => $company,
				'form'		=> $form->createView(),
				'radius'	=> $radius
		));
		
		
	}
}
