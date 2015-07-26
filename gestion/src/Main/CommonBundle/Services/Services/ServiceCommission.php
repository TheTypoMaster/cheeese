<?php 
namespace Main\CommonBundle\Services\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Prestations\Commission as CommissionPrestation;
use Main\CommonBundle\Entity\Photographers\Devis;
use Main\CommonBundle\Services\Session\ServiceSession;
use Main\CommonBundle\Services\Commission\ServiceCommission as CommissionParent;

class ServiceCommission
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

	private $serviceCommission;
	
	/**
	 *
	 * @param EntityManager $entityManager
	 * @param SecurityContext $securityContext
	 */
	public function __construct(EntityManager $entityManager, 
								SecurityContext $securityContext, 
								ServiceSession $service, 
								LoggerInterface $logger,
								CommissionParent $commissionService)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Prestations\Commission');
		$this->securityContext = $securityContext;
		$this->session = $service;
		$this->logger = $logger;
		$this->serviceCommission = $commissionService;
	}

	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	}

	/**
	 * [generateCommission description]
	 * @param  Devis  $devis [description]
	 * @return [type]        [description]
	 */
	public function generateCommission(Devis $devis)
	{
		$roles = $devis->getCompany()->getPhotographer()->getRoles();	
		if($devis->getCategory()->getType() == 1)
		{
			$commission = $this->serviceCommission->getCommissionParticulier();
			$commissionClient = $commission->getCustomer();			
		}elseif($devis->getCategory()->getType() == 2 ) {
			$commission = $this->serviceCommission->getCommissionEntreprise();
			$commissionClient = $commission->getCustomer();
		}
		if (in_array('ROLE_PHOTOGRAPHER_PREMIUM', $roles)){
			$commissionPhotographer = $commission->getPremium();
		}else{
			$commissionPhotographer = $commission->getPhotographer();
		}
		$commissionPrestation = new CommissionPrestation();
		$commissionPrestation->setCustomer($commissionClient);
		$commissionPrestation->setPhotographer($commissionPhotographer);
		return $commissionPrestation;
	}

	/**
	 * [editCommission description]
	 * @param  CommissionPrestation $commission [description]
	 * @param  [type]               $type       [description]
	 * @param  [type]               $params     [description]
	 * @return [type]                           [description]
	 */
	public function editCommission(CommissionPrestation $commission, $type, $params)
	{
		if (in_array('ROLE_ADMIN', $this->getCurrentUser()->getRoles()) ||
			in_array('ROLE_SUPER_ADMIN', $this->getCurrentUser()->getRoles())) {
			
			if ($type == 1) {
				$commission->setCustomer($params['customer']);
			}elseif($type == 2) {
				$commission->setPhotographer($params['photographer']);
			}
			$commission->setComment($params['comment']);
			try{
				$this->em->flush();
				$this->session->successFlashMessage('flash.message.commission.index');	
				return true;
			}catch(\Exception $e) {
				$this->session->errorFlashMessage();
				$this->logger->error($e->getMessage());
				return false;
			}
		}
	}
	
}