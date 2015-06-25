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
	 * @Rest\Get("/department/{department}/{country_id}/towns")
	 */
	public function townAction(Request $request, $department, $country_id)
	{
		$town = $this->get('service_town');
		return $town->findByDepartment($department, $country_id);
	}

}