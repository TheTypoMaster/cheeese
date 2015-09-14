<?php 

namespace Main\CommonBundle\Services\Offers;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Photographers\Devis;
use Main\CommonBundle\Entity\Photographers\DevisPrices;
use Main\CommonBundle\Services\Session\ServiceSession;

class ServicePrices 
{
	const ONE 	= 1;
	const TWO 	= 2;
	const THREE	= 3;
	const FOUR	= 4;
	const SIX	= 5;
	const EIGHT	= 6;
	const TEN	= 7;

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

	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, ServiceSession $service, LoggerInterface $logger)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Photographers\DevisPrices');
		$this->securityContext = $securityContext;
		$this->session = $service;
		$this->logger = $logger;
	}	

	/**
	 * [getPrices description]
	 * @param  Devis  $devis [description]
	 * @return [type]        [description]
	 */
	public function getPrices(Devis $devis)
	{
		return $this->repository->findBy(
			array('devis' =>$devis->getId()),
			array('duration' => 'ASC')
			);
	}

	public function createPrices(Devis $devis, $data) {
		if ($data['one'] != '') {
			$this->addPrice($devis, self::ONE, $data['one'], false);
		}
		if ($data['two'] != '') {
			$this->addPrice($devis, self::TWO, $data['two'], false);
		}
		if ($data['three'] != '') {
			$this->addPrice($devis, self::THREE, $data['three'], false);
		}
		if ($data['four'] != '') {
			$this->addPrice($devis, self::FOUR, $data['four'], false);
		}
		if ($data['six'] != '') {
			$this->addPrice($devis, self::SIX, $data['six'], false);
		}
		if ($data['eight'] != '') {
			$this->addPrice($devis, self::EIGHT, $data['eight'], false);
		}
		if ($data['ten'] != '') {
			$this->addPrice($devis, self::TEN, $data['ten'], false);
		}
	}

	/**
	 * [addPrice description]
	 * @param Devis  $devis    [description]
	 * @param [type] $duration [description]
	 * @param [type] $price    [description]
	 */
	public function addPrice (Devis $devis, $duration, $new, $flush = true) 
	{	
		$price = $this->fetch($devis, $duration);
		if($price)
		{
			return $this->editPrice($price, $new);
		}else {
			$price = new DevisPrices();
			$price->setDevis($devis);
			$price->setDuration($this->em->getRepository('MainCommonBundle:Utils\Duration')->findOneById($duration));
			$price->setPrice((double)$new);
			try{
				$this->em->persist($price);
				if ($flush) {
					$this->em->flush();
					$this->session->successFlashMessage('flash.message.devis.price.create');
				}				
				return true;
			}catch(\Exception $e){
				$this->session->errorFlashMessage();
				$this->logger->error($e->getMessage());
				return false;
			}
		}
		
	}

	/**
	 * [fetch description]
	 * @param  Devis  $devis    [description]
	 * @param  [type] $duration [description]
	 * @return [type]           [description]
	 */
	public function fetch (Devis $devis, $duration)
	{
		return $this->repository->findOneBy(array(
			'devis' => $devis->getId(),
			'duration' => $duration
			));

	}

	/**
	 * [updateStatus description]
	 * @param  DevisPrice $price  [description]
	 * @param  [type]     $status [description]
	 * @return [type]             [description]
	 */
	public function updateStatus(DevisPrices $price, $status)
	{
		$price->setActive($status);
		$price->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->flush();
			$this->session->successFlashMessage('flash.message.devis.price.edit');			
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
			return false;
		}
	}

	/**
	 * [editPrice description]
	 * @param  DevisPrices $price [description]
	 * @param  [type]      $new   [description]
	 * @return [type]             [description]
	 */
	public function editPrice(DevisPrices $price, $new) 
	{
		$price->setPrice((double)$new);
		$price->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->flush();
			$this->session->successFlashMessage('flash.message.devis.price.edit');			
			return true;
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
			return false;
		}
	}

	/**
	 * [getPricesByDuration description]
	 * @param  Devis  $devis [description]
	 * @return [type]        [description]
	 */
	public function getPricesByDuration(Devis $devis)
	{
		return $this->repository->getPricesByDuration($devis->getId());
	}

	/**
	 * [getPricesPublic description]
	 * @param  Devis  $devis [description]
	 * @return [type]        [description]
	 */
	public function getPricesPublic(Devis $devis)
	{
		return $this->repository->getPricesPublic($devis->getId());
	}
	
}