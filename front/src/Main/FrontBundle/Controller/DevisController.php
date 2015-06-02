<?php

namespace Main\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DevisController extends Controller
{
	/**
	 * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/book", name="devis_book")
	 * 
	 */
    public function devisBookAction(Request $request)
    {
    	$serviceSession = $this->get('service_session');
    	if (!$serviceSession->isDevisVariablesSet()) {
    		//Les variables ne sont pas la
    		//Redirection vers la homepage avec message Flash
    		return $this->redirect($this->generateUrl('index_public'));
    	}else {
    		//Début du traitement
    		$form = $this->createForm('form_front_devis_book', null, array());
    		$form->handleRequest($request);
    		if($request->isMethod('POST'))
    		{
    			$params = $request->request->get('form_front_devis_book');
    			if ($form->isValid())
    			{
    				$prestationService = $this->get('service_prestation');
					$prestationService->create($params['devis'], $params['town'], $params['day'], $params['address'], $params['startTime'], $params['message']);
    			}
    		}
    		return $this->render('MainFrontBundle:Devis:book.html.twig', array(
    				'form' => $form->createView()
    		));    		
    	}
    	
    }   

    /**
     *
     * @return Symfony\Component\HttpFoundation\Response
     * @Route("/devis/{id}", name="devis_show")
     */
    public function devisShowAction($id)
    {
    	
    }
    
    /**
     * @Route("/devis/add/{id}", name="devis_attach")
     * @param unknown $id
     */
    public function devisAdd($id)
    {
    	$serviceSession = $this->get('service_session');
    	$serviceSession->setDesiredDevis($id);
    	return $this->redirect($this->generateUrl('devis_book'));
    }
}
