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
		$Company = new Company();
		$Company->setPhotographer($user);
		$Company->setTown($this->em->getRepository('MainCommonBundle:Geo\Town')->findOneBy(array(
				'country'    => $data['country'],
				'department' => $data['department'],
				'name'	     => $data['town']	
				)));
		$Company->setIdentification($data['identification']);
		$Company->setTitle($data['title']);
		$Company->setAddress($data['address']);
		$Company->setStatus($this->em->getRepository('MainCommonBundle:Status\PhotographerStatus')->findOneById(self::TO_VERIFY));
		try{
			$this->em->persist($Company);
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
		$town = $this->em->getRepository('MainCommonBundle:Geo\Town')->findOneBy(array(
				'country' => $data['country'],
				'name'	  => $data['town']	
				));
		$Company->setTown($town);
		$Company->setIdentification($data['identification']);
		$Company->setTitle($data['title']);
		$Company->setAddress($data['address']);
		$Company->setUpdatedAt(new \DateTime('now'));
		try{
			$this->em->flush();
			return true;
		}catch(\Exception $e){
			$this->logger->error($e->getMessage());
			var_dump($e->getMessage());die;
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
		return $company->getStatus()->getId() == 2;
	}

	/**
	 * [countAll description]
	 * @return [type] [description]
	 */
	public function countAllBy($arg)
	{
		return $this->repository->countAllBy($arg);
	}
}