<?php

namespace Main\GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class CategoriesController extends Controller
{
    /**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/categories", name="categories")
	 */
	public function categoryAction(Request $request)
	{	
		$serviceCat = $this->get('service_categories');
		$categories = $serviceCat->getAllCategories();	
		return $this->render('MainGestionBundle:Utils:categories.html.twig', array(
			'categories' => $categories
			));	
	}

	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
     *
     * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/categories/{id}/update", name="geo_update_category")
	 */
	public function updateCategoryAction($id)
	{
		$serviceCat = $this->get('service_categories');
		$category = $serviceCat->findById($id);
		if(!$category) {
			return $this->redirect($this->generateUrl('categories'));
		}
		$update = $serviceCat->updateCat($category);
		if($update) {
			return $this->redirect($this->generateUrl('categories'));
		}
	}	
}
