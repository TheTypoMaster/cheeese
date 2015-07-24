<?php 

namespace Main\CommunityBundle\Controller\Offers;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Main\CommonBundle\Entity\Photographers\Devis;

class DevisController extends Controller
{
	//Create
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/new", name="devis_new")
	 */
	public function createAction(Request $request)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$form = $this->createForm('form_devis', new Devis(), array());
		$form->handleRequest($request);
		if($request->isMethod('POST'))
		{
			$params = $request->request->get('form_devis');
			if ($form->isValid())
			{
				$serviceDevis = $this->get('service_devis');
				$devis = $serviceDevis->create($params);
				if($devis) {
					return $this->redirect($this->generateUrl('devis_read', array( 
						'id' => $devis->getId()
						)
					));
				}
			}
				
		}
		return $this->render('MainCommunityBundle:Offers\Devis:new.html.twig', array(
				'form' 		=> $form->createView(),
		));
		
		
	}
	
	//List
	/**
	 * * @param Symfony\Component\HttpFoundation\Request $request Requête HTTP
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis", name="devis")
	 */
	public function viewAction(Request $request)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->read();
		return $this->render('MainCommunityBundle:Offers:devis.html.twig', array(
				'devis' => $devis
		));
		
	}
	
	//Read
	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/{id}", requirements={"id" = "\d+"}, name="devis_read")
	 */
	 public function readAction($id)
	 {
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->fetch($id);
		if(!$devis){
				return $this->redirect($this->generateUrl('devis'));
			}
		else
		{
			$servicePrestation = $this->get('service_prestation');
			$servicePrices = $this->get('service_prices');
			$serviceDevisBook = $this->get('service_devis_book');
			$prices = $servicePrices->getPrices($devis);
			$photos = $serviceDevisBook->getbook($devis);
			$prestations = $servicePrestation->getByDevis($devis->getId());
			return $this->render('MainCommunityBundle:Offers\Devis:index.html.twig', array(
					'devis' 	  => $devis,
					'prices'	  => $prices,
					'photos'	  => $photos,
					'services' 	  => $prestations
			));
		}
	}
	
	//Update
	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/edit/{id}", requirements={"id" = "\d+"}, name="devis_edit")
	 */
	public function editAction($id)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->fetch($id);
		if(!$devis){
			return $this->redirect($this->generateUrl('devis'));
		}else{
			$form = $this->createForm('form_devis', $devis, array());
			$request = $this->get('request');
			$form->handleRequest($request);
			if($request->isMethod('POST'))
			{
				$params = $request->request->get('form_devis');
				if ($form->isValid())
				{
					$edit = $serviceDevis->edit($devis, $params);
					if($edit) {
						return $this->redirect($this->generateUrl('devis_read', array(
							'id' => $edit->getId()
							)
						));
					}
				}
			
			}
			return $this->render('MainCommunityBundle:Offers\Devis:edit.html.twig', array(
					'devis' => $devis,
					'form'  => $form->createView()	
			));
		}
	}
	
	
	
	//Disable
	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/disable/{id}", requirements={"id" = "\d+"}, name="devis_disable")
	 */
	public function disableAction($id)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->fetch($id);
		if(!$devis){
			return $this->redirect($this->generateUrl('devis'));
		}else{
			$disable = $serviceDevis->changeActive($devis, 0);
			if($disable)
			return $this->redirect($this->generateUrl('devis'));
		}
	}	
	
	//Enable
	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/enable/{id}", requirements={"id" = "\d+"}, name="devis_enable")
	 */
	 public function enableAction($id)
	 {
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->fetch($id);
		if(!$devis){
			return $this->redirect($this->generateUrl('devis'));
		}else{
			$enable = $serviceDevis->changeActive($devis, 1);
			if($enable)
				return $this->redirect($this->generateUrl('devis'));
		}
	}

	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/{id}/price", requirements={"id" = "\d+"}, name="price_add")
	 */
	public function addPriceAction($id)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->fetch($id);
		if(!$devis){
				return $this->redirect($this->generateUrl('devis'));
			}
		else
		{
			$servicePrices = $this->get('service_prices');
			$form = $this->createForm('form_devis_prices', null, array(
				'new' => true,
				'devis'	=> $devis->getId()
				));
			$request = $this->get('request');
			$form->handleRequest($request);
			if($request->isMethod('POST'))
			{
				$params = $request->request->get('form_devis_prices');
				if ($form->isValid())
				{
					
					$add = $servicePrices->addPrice($devis, $params['duration'], $params['price']);
					if($add) {
							return $this->redirect($this->generateUrl('devis_read', array(
														'id' => $id
														)
													));
					}
				}
			
			}
			return $this->render('MainCommunityBundle:Offers\Devis:prices_form.html.twig', array(
					'form'  => $form->createView(),
					'devis'	=> $devis,
					'new'	=> true,
					'price'	=> null
			));
		}
	}

	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/{id}/status/{duration}/{status}", requirements={"id" = "\d+"}, name="price_edit_status")
	 */
	public function editStatusPrice($id, $duration, $status)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->fetch($id);
		if(!$devis){
				return $this->redirect($this->generateUrl('devis'));
			}
		$servicePrices = $this->get('service_prices');
		$price = $servicePrices->fetch($devis, $duration);
		if(!$price || $price->getDevis()->getCompany()->getPhotographer()->getId() !== $usr->getId()){
				return $this->redirect($this->generateUrl('devis'));
			}
		else{
			$update = $servicePrices->updateStatus($price, $status);
			if($update) {
				return $this->redirect($this->generateUrl('devis_read', array(
														'id' => $id
														)
													));
			}
		}

	}
	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/{id}/price/{duration}", requirements={"id" = "\d+"}, name="price_edit")
	 */
	public function editPrice($id, $duration)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->fetch($id);
		if(!$devis){
				return $this->redirect($this->generateUrl('devis'));
			}
		$servicePrices = $this->get('service_prices');
		$price = $servicePrices->fetch($devis, $duration);
		if(!$price || $price->getDevis()->getCompany()->getPhotographer()->getId() !== $usr->getId()){
				return $this->redirect($this->generateUrl('devis'));
			}
		else{
			$form = $this->createForm('form_devis_prices', $price, array(
				'new' => false
				));
			$request = $this->get('request');
			$form->handleRequest($request);
			if($request->isMethod('POST'))
			{
				$params = $request->request->get('form_devis_prices');
				if ($form->isValid())
				{					
					$edit = $servicePrices->editPrice($price, $params['price']);
					if($edit) {
							return $this->redirect($this->generateUrl('devis_read', array(
														'id' => $id
														)
													));
					}
				}
			
			}
			return $this->render('MainCommunityBundle:Offers\Devis:prices_form.html.twig', array(
					'form'  => $form->createView(),
					'devis'	=> $devis,
					'new' 	=> false,
					'price'	=> $price
			));
		}
	}

	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/{id}/photo", requirements={"id" = "\d+"}, name="photo_add")
	 */
	public function addPhotoAction($id)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->fetch($id);
		if(!$devis){
				return $this->redirect($this->generateUrl('devis'));
			}
		else
		{
			$form = $this->createForm('form_devis_book', null, array());
			$request = $this->get('request');
			$form->handleRequest($request);
			if($request->isMethod('POST'))
			{
				$params = $request->request->get('form_devis_book');
				if ($form->isValid())
				{
					$servicePhoto = $this->get('service_devis_book');
					$add = $servicePhoto->addPhoto($devis, $form->getData());					
					if($add) {
							return $this->redirect($this->generateUrl('photo_manage', array(
														'id' => $id
														)
													));
					}
				}
			
			}
			return $this->render('MainCommunityBundle:Offers\Devis:book_form.html.twig', array(
					'form'  => $form->createView(),
					'devis'	=> $devis
			));
		}
	}

	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/{id}/manage-photos", requirements={"id" = "\d+"}, name="photo_manage")
	 */
	public function managePhotoAction($id)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->fetch($id);
		if(!$devis){
				return $this->redirect($this->generateUrl('devis'));
			}
		else
		{
			$servicePhoto = $this->get('service_devis_book');
			$photos = $servicePhoto->getBook($devis);
			$cover = $servicePhoto->hasCover($photos);
			return $this->render('MainCommunityBundle:Offers\Devis:book_manage.html.twig', array(
					'photos'	=> $photos,
					'devis'		=> $devis,
					'cover'		=> $cover
			));
		}
	}

	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/{id}/delete-photos/{url}", requirements={"id" = "\d+"}, name="photo_delete")
	 */
	public function deletePhotoAction($id, $url)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->fetch($id);
		if(!$devis){
				return $this->redirect($this->generateUrl('devis'));
			}
		$servicePhoto = $this->get('service_devis_book');
		$photo = $servicePhoto->fetchByUrl($url);
		if(!$photo || ($id != $photo->getDevis()->getId())) {
			return $this->redirect($this->generateUrl('devis'));
		}
		$delete = $servicePhoto->deletePhoto($photo);
		if($delete)
		{
			return $this->redirect($this->generateUrl('photo_manage', array(
														'id' => $id
														)
													));
		}

	}

	/**
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 * @Route("/devis/{id}/cover-photos/{url}", requirements={"id" = "\d+"}, name="photo_cover")
	 */
	public function setCoverPhotoAction($id, $url)
	{
		$usr= $this->get('security.context')->getToken()->getUser();
		//@TODO: Check that the user has the rights
		$serviceDevis = $this->get('service_devis');
		$devis = $serviceDevis->fetch($id);
		if(!$devis){
				return $this->redirect($this->generateUrl('devis'));
			}
		$servicePhoto = $this->get('service_devis_book');
		$photo = $servicePhoto->fetchByUrl($url);
		if(!$photo || ($id != $photo->getDevis()->getId())) {
			return $this->redirect($this->generateUrl('devis'));
		}
		$update = $servicePhoto->setcoverPhoto($photo, $devis);
		if($update)
		{
			return $this->redirect($this->generateUrl('photo_manage', array(
														'id' => $id
														)
													));
		}

	}

}
