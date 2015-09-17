<?php

namespace Main\GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class GeoController extends Controller
{
    /**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/geo", name="geo")
	 */
	public function geoAction(Request $request)
	{	
		$serviceGeo = $this->get('service_department');
		$depts = $serviceGeo->getAllDepts();	
		return $this->render('MainGestionBundle:Geo:index.html.twig', array(
			'depts' => $depts
			));	
	}

	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/geo/{country}/dept/{code}", name="geo_update_dept")
	 */
	public function updateDeptAction($country, $code)
	{
		$serviceGeo = $this->get('service_department');
		$dept = $serviceGeo->findByCodeAndCountry($code, $country);
		if(!$dept) {
			return $this->redirect($this->generateUrl('geo'));
		}
		$update = $serviceGeo->updateDept($dept);
		if($update) {
			return $this->redirect($this->generateUrl('geo'));
		}
	}

	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/dept/{code}", name="geo_dept")
	 */
	public function deptAction($code) 
	{
		$serviceGeo = $this->get('service_department');
		$dept = $serviceGeo->findByCode($code);
		if(!$dept) {
			return $this->redirect($this->generateUrl('geo'));
		}
		$serviceCompany = $this->get('service_company');
		$companies = $serviceCompany->getCompaniesByDept($code);
		$groupbyStatus = $serviceCompany->groupByDept($code);
		return $this->render('MainGestionBundle:Geo:department.html.twig', array(
			'department' 	=> $dept,
			'companies'	 	=> $companies,
			'groupByStatus'	=> json_encode($groupbyStatus),
			));
	}

	
}
