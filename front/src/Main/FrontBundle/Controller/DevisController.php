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
     *
     * @return Symfony\Component\HttpFoundation\Response
     * @Route("/devis/{id}", name="devis_show")
     */
    public function devisShowAction($id)
    {
    	$devisService = $this->get('service_devis');
        $request = $this->get('request');
        //TODO: Verifier le statut de la compagnie
        $devis = $devisService->getDevisPublic($id);
        if (!$devis) {
            throw $this->createNotFoundException('A customiser');
        }
        $bookService = $this->get('service_devis_book');
        $priceService = $this->get('service_prices');
        $photos = $bookService->getBook($devis);
        $prices = $priceService->getPricesByDuration($devis);
        $comments = null;
        if ($devis->getPrestations() > 0)
        {   
            $notation = $this->get('service_notation');
            $comments = $notation->getDevisEvaluation($devis);
        }
        $form   = null;
        $radius = null;
        $towns  = null;
        $dates  = null;
        $town = null;
        $date = null;
        $search = false;
        if ($this->container->get('security.context')->isGranted('ROLE_PARTICULIER')) {
            $availabilityService = $this->get('service_availability');
            $townService = $this->get('service_town');
            $movesService = $this->get('service_moves_radius');
            $dates = $availabilityService->findDatesByCompany($devis->getCompany()->getId());
            $radius = $movesService->getRadius($devis->getCompany());
            $towns =  $townService->findTownsByCompany($devis->getCompany());
            $bookable = true;
            if ($towns == null || $dates == null || $radius == null ) {
                //Le photographe n'a pis mis à jour son offre,
                //Il est donc pas possible de faire une demande
                $bookable = false;
            }else{
                //On peut faire une demande
                //Pré-remplissage du formulaire
                $serviceSession = $this->get('service_session');
                $args = $serviceSession->getSearchArgs();
                if($args) {
                    $search = true;
                    if(isset($args['town']) && $args['town'] != ""){
                        $town = $townService->isTownAvailable($args['town'], $args['town_text'], $towns);
                    }
                    if(isset($args['day']) && $args['day'] != ""){
                        $date = $availabilityService->isDateAvailable($args['day'], $dates);
                    }
                }
                $towns =  json_encode($towns);
                $dates = json_encode($dates);
                $formbook = $this->createForm('form_front_devis_book', null, array(
                    'devis'         => $devis->getId(),
                    'town'          => $town,
                    'date'          => $date
                ));
                $form = $formbook->createView();
                $formbook->handleRequest($request);
                if($request->isMethod('POST'))
                {
                    $params = $request->request->get('form_front_devis_book');
                    if ($formbook->isValid())
                    {
                        $prestationService = $this->get('service_prestation');
                        $new = $prestationService->create($devis, $params['town_code'], $params['day'], $params['address'], $params['startTime'], $params['duration'], $params['message']);
                        return $this->redirect($this->generateUrl('show_service', array(
                        'id' => $new->getId()
                        )));
                    }
                }
            }
            
        }
        return $this->render('MainFrontBundle:Devis\\show:index.html.twig', array(
            'devis'     => $devis,
            'photos'    => $photos,
            'prices'    => json_encode($prices),
            'comments'  => $comments,
            'bookable'  => $bookable,
            'radius'    => $radius,
            'form'      => $form,
            'towns'     => $towns,
            'dates'     => $dates,
            'search'    => $search
            ));
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
