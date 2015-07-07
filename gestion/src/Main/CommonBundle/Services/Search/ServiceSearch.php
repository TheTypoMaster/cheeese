<?php 

namespace Main\CommonBundle\Services\Search;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Main\CommonBundle\Entity\Photographers\Devis;
use Main\CommonBundle\Entity\Photographers\DevisPrices;
use Main\CommonBundle\Entity\Photographers\DevisBook;

class ServiceSearch 
{
	/**
	 *
	 * @var EntityManager
	 */
	private $em;
	
	/**
	 *
	 * @var string
	 */
	private $repository;
	
	private $securityContext;

	private $logger;
	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, LoggerInterface $logger)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Photographers\Devis');
		$this->securityContext = $securityContext;
		$this->logger = $logger;
	}

	/**
	 *
	 */
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	}

	/**
	 * Formulaire de recherche de devis (sans filtre)
	 * @param unknown $category
	 * @param unknown $town
	 * @param unknown $day
	 */
	public function searchFirstStep($category, $town, $day, $ip)
	{
		$user = 0;
		if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
			$user = $this->getCurrentUser()->getId();
		}
		$date = new \DateTime(str_replace('/', '-', $day));
		$end = array();
		$results = $this->repository->findDevisFront($category, $town, $date->format('Y-m-d'));
		foreach ($results as $result) {
			if ($result instanceof Devis) {
				$end[$result->getId()]['devis'] = $result;
			}
			if ($result instanceof DevisBook) {
				$end[$result->getDevis()->getId()]['book'] = $result;
			}
			if ($result instanceof DevisPrices) {
				$end[$result->getDevis()->getId()]['prices'][] = $result;
			}

		}
		
		$tolog = array(
				'User'		=> $user,
				'Ip'		=> $ip,
				'Date'		=> $date->format('Y-m-d'),
				'Category'	=> $category,
				'Town'		=> $town,
				'Results'	=> count($end)
			);
		$this->logger->info(json_encode($tolog));
		return $end;
	}
	
	
}