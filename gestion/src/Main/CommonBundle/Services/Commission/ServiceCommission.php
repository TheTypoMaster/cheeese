<?php 

namespace Main\CommonBundle\Services\Commission;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Main\CommonBundle\Entity\Utils\Commission;
use Main\CommonBundle\Services\Session\ServiceSession;


class ServiceCommission
{
	/**
	 *
	 * @var EntityManager
	 */
	private $em;
	private $session;

	private $logger;
	
	/**
	 * 
	 * @var string
	 */
	private $repository;
	
	public function __construct(EntityManager $entityManager, ServiceSession $service, LoggerInterface $logger)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Utils\Commission');
		$this->session = $service;
		$this->logger = $logger;
	}	

	/**
	 * [getAllDepts description]
	 * @return [type] [description]
	 */
	public function getAll()
	{

		return $this->repository->findCommissions();
	}

	/**
	 * [getCommissionParticulier description]
	 * @return [type] [description]
	 */
	public function getCommissionParticulier()
	{
		return $this->repository->findCommissionParticuliers();
	}

	/**
	 * [getCommissionEntreprise description]
	 * @return [type] [description]
	 */
	public function getCommissionEntreprise()
	{
		return $this->repository->findCommissionEntreprises();
	}

	/**
	 * [updateCommission description]
	 * @param  Commission $commission [description]
	 * @param  [type]     $params     [description]
	 * @return [type]                 [description]
	 */
	public function updateCommission(Commission $commission, $params)
	{
		$commission->setCustomer($params['customer']);
		$commission->setPhotographer($params['photographer']);
		$commission->setPremium($params['premium']);
		$cacheDriver = $this->em->getConfiguration()->getResultCacheImpl();
        $cacheDriver->delete('findCommissions');
        if($commission->getId() == 1) {
        	$cacheDriver->delete('findCommissionParticuliers');
        }else {
        	$cacheDriver->delete('findCommissionEntreprises');
        }
		try{
			$this->em->flush();
			$this->session->successFlashMessage('flash.message.commission.index');
		}catch(\Exception $e){
			$this->session->errorFlashMessage();
			$this->logger->error($e->getMessage());
		}
	}

}