<?php 

namespace Main\CommonBundle\Services\Offers;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Photographers\Devis;
use Main\CommonBundle\Services\Session\ServiceSession;
use Main\CommonBundle\Services\Offers\ServiceDevisBook;
use Main\CommonBundle\Services\Offers\ServicePrices;


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

	private $session;

	private $logger;

	private $devisBook;

	private $devisPrices;

	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, ServiceSession $service, LoggerInterface $logger, ServiceDevisBook $book, ServicePrices $prices)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Photographers\Devis');
		$this->securityContext = $securityContext;
		$this->session = $service;
		$this->logger = $logger;
		$this->devisBook = $book;
		$this->devisPrices = $prices;
	}
	
	/**
	 * Get Current user
	 */
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	}
	
	protected function getCurrentCompany() {
		return $this->em->getRepository('MainCommonBundle:Companies\Company')->findOneBy(array(
				'photographer' => $this->getCurrentUSer()->getId()
			));
	}
	
	/**
	 * Get all devis
	 */
	public function read()
	{
		return $this->repository->getCompanyDevis($this->getCurrentCompany()->getId());
	}

	/**
	 * Get all devis from a given company
	 */
	public function getAllByCompany($id)
	{
		return $this->repository->findBy(array(
			'company' => $id
			));
	}
	
	/**
	 * 
	 * @param unknown $data
	 * @return boolean
	 */
	public function create($data)
	{
		$devis 		= $data['details'];
		$prices 	= $data['prices'];
		$book 		= $data['photos'];
		$company  	= $this->getCurrentCompany();
		$devis->setCompany($company);
		try{
			$this->em->persist($devis);
			$this->em->flush();
			$this->devisPrices->createPrices($devis, $prices);
			$this->devisBook->createBook($devis, $book);
			$this->em->flush();
			$this->session->successFlashMessage('flash.message.devis.create');
			return $devis;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
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
				'company'  		=> $this->getCurrentCompany()->getId()
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
		$currency = $this->em->getRepository('MainCommonBundle:Utils\Currency')->findOneById($data['currency']);
		
		$devis->setTitle($data['title']);
		$devis->setCategory($category);
		$devis->setCurrency($currency);
		$devis->setPresentation($data['presentation']);
		$devis->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->flush();
			$this->session->successFlashMessage('flash.message.devis.edit');
			return $devis;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
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
			$this->em->flush();
			$this->session->successFlashMessage('flash.message.devis.edit');
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
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

	public function CountAllMyActiveDevis()
	{
		return $this->countAllActive($this->getCurrentCompany()->getId());
	}

	/**
	 * [countAll description]
	 * @return [type] [description]
	 */
	public function countAllActive($user = null)
	{
		return $this->repository->countAllActive($user);
	}

	/**
	 * [groupBy description]
	 * @param  [type] $user [description]
	 * @return [type]       [description]
	 */
	public function groupBy($user = null) 
	{
		return $this->repository->groupBy($user);
	}

	public function groupMyDevis()
	{
		return $this->groupBy($this->getCurrentCompany()->getId());
	}
	
	/**
	 * [updateNotation description]
	 * @param  [type] $devis [description]
	 * @return [type]        [description]
	 */
	public function updateNotation($devis)
	{
		return $this->repository->updateNotation($devis, new \DateTime('now'));
	}
	
	/**
	 * [getDevisPublic description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function getDevisPublic($id)
	{
		return $this->repository->getDevisPublic($id);
	}
	
	/**
	 * [isDevisStudio description]
	 * @param  Devis   $devis [description]
	 * @return boolean        [description]
	 */
	public function isDevisStudio(Devis $devis)
	{
		return $devis->getCategory()->getName() == 'Shooting Studio';
	}
	
}