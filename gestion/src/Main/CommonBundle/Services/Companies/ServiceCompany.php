<?php 

namespace Main\CommonBundle\Services\Companies;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Main\CommonBundle\Entity\Companies\Company;
use Main\CommonBundle\Services\Emails\ServiceEmail;

class ServiceCompany 
{
	const TO_VERIFY 			= 1;
	const VERIFICATION_OK 		= 2;
	const VERIFICATION_KO		= 3;
	const SUPSENDED				= 4;

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

	private $mailer;

	private $logger;
	
	public function __construct(EntityManager $entityManager, SecurityContext $securityContext, ServiceEmail $mailer, LoggerInterface $logger)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Companies\Company');
		$this->securityContext = $securityContext;
		$this->mailer = $mailer;
		$this->logger = $logger;
	}	
	
	/**
	 * Get Current user
	 */
	protected function getCurrentUser(){
		return $this->securityContext->getToken()->getUser();
	}
	
	/**
	 * 
	 * @param unknown $id
	 */
	public function getCompany($id)
	{
		return $this->repository->getCompany($id);
	}

	/**
	 * 
	 */
	public function getCurrentCompany()
	{
		return $this->repository->getCompany($this->getCurrentUser()->getId());
	}
	
	/**
	 * 
	 * @param unknown $data
	 * @return boolean
	 */
	public function create($data)
	{
		$user = $this->getCurrentUser();
		$townStudio = null;
		if ($data['town_studio_id'] != '') {
			$townStudio = $this->em->getRepository('MainCommonBundle:Geo\Town')->findOneById($data['town_studio_id']);
		}
		if ($data['town_id'] == '') {
			$town = $this->em->getRepository('MainCommonBundle:Geo\Town')->findOneBy(array(
				'country'    => $data['country'],
				'department' => $data['department'],
				'name'	     => $data['town']	
				));
		}else {
			$town = $this->em->getRepository('MainCommonBundle:Geo\Town')->findOneById($data['town_id']);
		}
		$Company = new Company();
		$Company->setPhotographer($user);
		$Company->setTown($town);
		$Company->setIdentification($data['identification']);
		$Company->setTitle($data['title']);
		$Company->setAddress($data['address']);
		$Company->setStudio($data['title_studio']);
		$Company->setStudioAddress($data['address_studio_numero'].';;;'.$data['address_studio']);
		$Company->setStudioTown($townStudio);
		$Company->setStatus($this->em->getRepository('MainCommonBundle:Status\PhotographerStatus')->findOneById(self::TO_VERIFY));
		try{
			$this->em->persist($Company);
			if(isset($data['firstname'])) {
				$user->setFirstName($data['firstname']);
				$user->setLastName($data['lastname']);
			}
			$this->em->flush();
			return true;
		}catch(\Exception $e){
			$this->logger->error($e->getMessage());
			return false;
		}
	}
	
	/**
	 * 
	 * @param unknown $company
	 */
	public function update(Company $Company, $data)
	{
		$town = $this->em->getRepository('MainCommonBundle:Geo\Town')->findOneById($data['town_id']);
		$townStudio = null;
		if ($data['town_studio_id'] != '') {
			$townStudio = $this->em->getRepository('MainCommonBundle:Geo\Town')->findOneById($data['town_studio_id']);
		}
		$Company->setTown($town);
		$Company->setStudioTown($townStudio);
		if (isset($data['identification'])) {
			$Company->setIdentification($data['identification']);	
		}			
		$Company->setTitle($data['title']);
		$Company->setAddress($data['address']);
		$Company->setStudio($data['title_studio']);
		$Company->setStudioAddress($data['address_studio_numero'].';;;'.$data['address_studio']);
		$Company->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->flush();
			return true;
		}catch(\Exception $e){
			$this->logger->error($e->getMessage());
			return false;
		}
	}

	/**
	* Vérifie le nombre de compagnies par encore vérifiées
	*
	*/
	public function getCountUnverifiedCompanies() 
	{
		return $this->countAllBy(1) > 0 ;
	}

	/**
	*	retourne la liste des photographes a verifier
	*
	*/
	public function getPhotographersToVerify()
	{
		return $this->repository->findByStatus(1);
	}
	
	/**
	*
	*
	*/
	public function verifyPhotographer(Company $company, $result)
	{
		if(in_array('ROLE_ADMIN', $this->getCurrentUser()->getRoles()) 
			|| in_array('ROLE_SUPER_ADMIN', $this->getCurrentUser()->getRoles())) 
		{
			if($result == '0') {
				$status = $this->em->getRepository('MainCommonBundle:Status\PhotographerStatus')->findOneById(self::VERIFICATION_KO);
			}elseif ($result == '1') {
				$status = $this->em->getRepository('MainCommonBundle:Status\PhotographerStatus')->findOneById(self::VERIFICATION_OK);
			}
			$company->setUpdatedAt(new \DateTime('now'));
			$company->setStatus($status);
			try{
				$this->em->flush();
				//Envoi du mail
            	$this->mailer->companyVerificationEmail($company->getPhotographer(),$company->getStatus()->getId());
				return true;
		}catch(\Exception $e){
			$this->logger->error($e->getMessage());
			return false;
		}
		}
	}

	/**
	*	On vérifie que la companie est bien vérifiée
	*
	*/
	public function isVerifiedCompany(Company $company)
	{
		return $company->getStatus()->getId() == self::VERIFICATION_OK || $company->getStatus()->getId() == self::SUPSENDED;
	}

	/**
	 * [countAll description]
	 * @return [type] [description]
	 */
	public function countAllBy($arg)
	{
		return $this->repository->countAllBy($arg);
	}

	/**
	 * [canDoStudio description]
	 * @param  Company $company [description]
	 * @return [type]           [description]
	 */
	public function canDoStudio(Company $company)
	{
		return $company->getStudioTown() != null;
	}

	/**
	 * [resumeCompany description]
	 * @param  Company $company [description]
	 * @return [type]           [description]
	 */
	public function resumeCompany(Company $company)
	{
		$company->setStatus($this->em->getRepository('MainCommonBundle:Status\PhotographerStatus')->findOneById(self::VERIFICATION_OK));
		$company->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->flush();
			$this->mailer->companyVerificationEmail($company->getPhotographer(),$company->getStatus()->getId());
			return true;
		}catch(\Exception $e){
			$this->logger->error($e->getMessage());
			
			return false;
		}
	}

	/**
	 * [suspendCompany description]
	 * @param  Company $company [description]
	 * @return [type]           [description]
	 */
	public function suspendCompany(Company $company)
	{
		$company->setStatus($this->em->getRepository('MainCommonBundle:Status\PhotographerStatus')->findOneById(self::SUPSENDED));
		$company->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->flush();
			$this->mailer->companyVerificationEmail($company->getPhotographer(),$company->getStatus()->getId());
			return true;
		}catch(\Exception $e){
			$this->logger->error($e->getMessage());
			return false;
		}
	}
}