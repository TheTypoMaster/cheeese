<?php 

namespace Main\CommunityBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

class GeoController extends Controller
{
	/**
	 * @Rest\View
	 * @Rest\Get("/country/{country_id}/town")
	 */
	public function townAction(Request $request, $country_id)
	{
		$town = $this->get('service_town');
		$contient = $request->query->get('contient');
		return $town->findByCountry($country_id, $contient);
	}

}