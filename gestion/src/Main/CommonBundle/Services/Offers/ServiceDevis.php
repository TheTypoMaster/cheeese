<?php 

namespace Main\CommonBundle\Services\Offers;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Photographers\Devis;

class ServiceDevis 
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
		$this->repository = $this->em->getRepository('MainCommonBundle:Photographers\Devis');
		$this->securityContext = $securityContext;
	}
	
	/**
	 * Get Current user
	 */
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	}
	
	protected function getCurrentCompany() {
		return $this->em->getRepository('MainCommonBundle:Companies\Company')->findOneByPhotographer($this->getCurrentUSer()->getId());
	}
	
	/**
	 * Get all devis
	 */
	public function read()
	{
		return $this->repository->findBy(array('company' => $this->getCurrentUSer()->getId()));
	}

	/**
	 * Get all devis from a given company
	 */
	public function getAllByCompany($id)
	{
		return $this->repository->findBy(array('company' => $id));
	}
	
	/**
	 * 
	 * @param unknown $data
	 * @return boolean
	 */
	public function create($data)
	{
		$category = $this->em->getRepository('MainCommonBundle:Utils\Category')->findOneById($data['category']);
		$duration = $this->em->getRepository('MainCommonBundle:Utils\Duration')->findOneById($data['duration']);
		$currency = $this->em->getRepository('MainCommonBundle:Utils\Currency')->findOneById($data['currency']);
	
		$devis = new Devis();
		$devis->setTitle($data['title']);
		$devis->setCompany($this->getCurrentCompany());
		$devis->setCategory($category);
		$devis->setDuration($duration);
		$devis->setPrice($data['price']);
		$devis->setCurrency($currency);
		$devis->setPresentation($data['presentation']);
		try{
			$this->em->persist($devis);
			$this->em->flush();
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
			return false;
		}
	}
	
	/**
	 * 
	 * @param int $devis
	 */
	public function fetch($devis)
	{
		return $this->repository->findOneBy(array(
				'id'			=> $devis,
				'company'  		=> $this->getCurrentUSer()->getId()
				));
	}

	/**
	*
	* @param int $devis
	*/
	public function fetchPublic($devis)
	{
		return $this->repository->findOneBy(array(
				'id'			=> $devis,
				));
	}
	
	/**
	 * 
	 */
	 public function edit(Devis $devis, $data)
	{
		$category = $this->em->getRepository('MainCommonBundle:Utils\Category')->findOneById($data['category']);
		$duration = $this->em->getRepository('MainCommonBundle:Utils\Duration')->findOneById($data['duration']);
		$currency = $this->em->getRepository('MainCommonBundle:Utils\Currency')->findOneById($data['currency']);
		
		$devis->setTitle($data['title']);
		$devis->setCategory($category);
		$devis->setDuration($duration);
		$devis->setPrice($data['price']);
		$devis->setCurrency($currency);
		$devis->setPresentation($data['presentation']);
		$devis->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->persist($devis);
			$this->em->flush();
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
			return false;
		}
	}
	
	/**
	 * 
	 * @param Devis $devis
	 * @return boolean
	 */
	public function changeActive(Devis $devis, $value)
	{
		$devis->setUpdatedAt(new \DateTime('now'));
		$devis->setActive($value);
		try{
			$this->em->persist($devis);
			$this->em->flush();
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
			return false;
		}
	}
	
	/**
	 * 
	 * @param Devis $devis
	 * @return boolean
	 */
	public function hasPrestations(Devis $devis)
	{
		return $devis->getPrestations() > 0;
	}

	/**
	*
	*
	*/
	public function getAllDevis()
	{
		return $this->repository->findBy(array(), array('createdAt' => 'desc'));
	}
	
	
}