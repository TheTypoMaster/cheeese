<?php 

namespace Main\CommonBundle\Services\Search;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
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
	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Photographers\Devis');
		$this->securityContext = $securityContext;
	}
	/**
	 * Formulaire de recherche de devis (sans filtre)
	 * @param unknown $category
	 * @param unknown $town
	 * @param unknown $day
	 */
	public function searchFirstStep($category, $town, $day)
	{
		$date = new \DateTime(str_replace('/', '-', $day));
		$results = $this->repository->findDevisFront($category, $town, $date->format('Y-m-d'));
		$end = array();
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
		return $end;
	}
	
	
}