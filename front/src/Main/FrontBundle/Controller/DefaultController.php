<?php

namespace Main\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/", name="index_public")
	 */
    public function indexPublicAction(Request $request)
    {
    	$form = $this->createForm('form_front_search', null, array(
    			'type' 	  => 1 
    			));
        
    	$form->handleRequest($request);
    	$results = null;
    	$post = false;
    	if($request->isMethod('POST'))
    	{
    		$params = $request->request->get('form_front_search');
    		$post = true;
    		if ($form->isValid())
    		{
    			//Recherche
    			$serviceSearch = $this->get('service_search');
    			$results = $serviceSearch->searchFirstStep($params['category'], $params['town_code'], $params['day'], $request->getClientIp());
    			if(count($results) > 0) {
    				//Mise en session des parametres de recherche (sinon ca ne sert a rien de stocker en session)
    				$serviceSession = $this->get('service_session');
    				$serviceSession->setSearchArgs($params['category'], $params['town_code'], $params['town_text'], $params['day']);
    			}
    		}
    	
    	}
    	return $this->render('MainFrontBundle:Default:index.html.twig', array(
				'form' 		=> $form->createView(),
    			'post'		=> $post,
    			'type' 	  	=> 1,
    			'country'   => 1,
    			'results'	=> $results
		));
    }   

    /**
     * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
     * @Route("/entreprise", name="index_entreprise")
     */
    public function indexEntrepriseAction(Request $request)
    {
    	$form = $this->createForm('form_front_search', null, array(
    			'country' => 1,
    			'type' 	  => 2 
    			));
    	$form->handleRequest($request);
    	$results = null;
    	$post = false;

    	if($request->isMethod('POST'))
    	{
    		$params = $request->request->get('form_front_search');
    		$post = true;
    		if ($form->isValid())
    		{
    			$serviceSearch = $this->get('service_search');
    			$results = $serviceSearch->searchFirstStep($params['category'], $params['town_code'], $params['day']);
    		}
    		 
    	}
    	return $this->render('MainFrontBundle:Default:index.html.twig', array(
				'form' 		=> $form->createView(),
    			'type' 	  	=> 2,
    			'post'		=> $post,
    			'country'   => 1,
    			'results'	=> $results
		));
    }
}
