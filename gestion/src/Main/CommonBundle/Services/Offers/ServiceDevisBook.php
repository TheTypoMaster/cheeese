<?php 

namespace Main\CommonBundle\Services\Offers;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Photographers\Book;
use Main\CommonBundle\Entity\Photographers\Devis;


class ServiceDevisBook 
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
	
	protected $securityContext;
	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Photographers\Book');
		$this->securityContext = $securityContext;
	}

	/**
	 * [getBook description]
	 * @param  Devis  $devis [description]
	 * @return [type]        [description]
	 */
	public function getBook(Devis $devis)
	{
		return null;
	}
	
	
}