<?php 

namespace Main\CommonBundle\Services\Search;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Main\CommonBundle\Entity\Photographers\Devis;

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
	
	private $container;
	
	public function __construct(EntityManager $entityManager, ContainerInterface $container)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Photographers\Devis');
		$this->container = $container;
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
		return $this->repository->findDevisFront($category, $town, $date->format('Y-m-d'));
	}
	
	
}