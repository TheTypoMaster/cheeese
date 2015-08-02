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
    	$form = $this->createForm('form_front_index_search', null, array());        
    	$form->handleRequest($request);
    	$results = null;
    	$post = false;
    	if($request->isMethod('POST'))
    	{
    		$params = $request->request->get('form_front_index_search');
    		if ($form->isValid())
    		{
                $serviceSession = $this->get('service_session');
                $serviceSession->remove('front_search');
                $serviceSession->setSearchArgs($params['category']);
    			return $this->redirect($this->generateUrl('search'));
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
     * [searchAction description]
     * @param  Request $request [description]
     * @return [type]           [description]
     * @Route("/search", name="search")
     */
    public function searchAction(Request $request)
    {   
        $serviceSession = $this->get('service_session');
        $serviceSearch = $this->get('service_search');
        $results = array();
        $search = false;
        if ($serviceSession->isSearchVariablesSet()){
            $search = true;
            $results = $serviceSearch->searchFirstStep($serviceSession->getSearchArgs(), $request->getClientIp());
        }
        $form = $this->createForm('form_front_search', null, array());
        $form->handleRequest($request);
        if($request->isMethod('POST'))
        {
            $params = $request->request->get('form_front_search');                       
            if ($form->isValid())
            {
                $serviceSession->setSearchArgs($params['category'], $params['town_code'], $params['town_text'], $params['day']);
                $search = true;
                $results = $serviceSearch->searchFirstStep($serviceSession->getSearchArgs(), $request->getClientIp());
             }      
        }
        return $this->render('MainFrontBundle:Default:search.html.twig', array(
            'form'      => $form->createView(),
            'search'    => $search,
            'results'   => $results
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

    /**
     * [aboutAction description]
     * @param  Request $request [description]
     * @return [type]           [description]
     * @Route("/about", name="about")
     */
    public function aboutAction(Request $request)
    {
        return $this->render('MainFrontBundle:Default:about.html.twig');
    }
}
