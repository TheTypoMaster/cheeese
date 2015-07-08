<?php

namespace Main\CommunityBundle\Controller\Companies;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;

class TransactionController extends Controller
{
	
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/transactions", name="transactions")
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
			$transactionService = $this->get('service_transaction');
			$transactions = $transactionService->getByUser($company);
			return $this->render('MainCommunityBundle:Company:transactions.html.twig', array(
					'transactions' 	=> $transactions,
			));
			
		}
	}
}
