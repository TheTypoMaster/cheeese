<?php 

namespace Main\CommonBundle\Services\Companies;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Main\CommonBundle\Entity\Companies\Company;

class ServiceCompany 
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
		$this->repository = $this->em->getRepository('MainCommonBundle:Companies\Company');
		$this->container = $container;
	}	
	
	/**
	 * Get Current user
	 */
	protected function getCurrentUser(){
		return $this->container->get('security.context')->getToken()->getUser();
	}
	
	/**
	 * 
	 * @param unknown $id
	 */
	public function getCompany($id)
	{
		return $this->repository->findOneBy(array('photographer' => $id ));
	}
	
	/**
	 * 
	 * @param unknown $data
	 * @return boolean
	 */
	public function create($data)
	{
		$user = $this->getCurrentUser();
		$country = $this->em->getRepository('MainCommonBundle:Geo\Country')->findOneById($data['country']);
		$town = $this->em->getRepository('MainCommonBundle:Geo\Town')->findOneBy(array(
				'country' => $data['country'],
				'name'	  => $data['town']	
				));
		$status = $this->em->getRepository('MainCommonBundle:Status\PhotographerStatus')->findOneById(1);//TODO change avec constant
		
		$Company = new Company();
		$Company->setPhotographer($user);
		$Company->setTown($town);
		$Company->setCountry($country);
		$Company->setIdentification($data['identification']);
		$Company->setTitle($data['title']);
		$Company->setAddress($data['address']);
		$Company->setStatus($status);
		try{
			$this->em->persist($Company);
			if(isset($data['firstname'])) {
				$user = $this->getCurrentUser();
				$user->setFirstName($data['firstname']);
				$user->setLastName($data['lastname']);
				$this->em->persist($user);
			}
			$this->em->flush();
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
			return false;
		}
	}
	
	/**
	 * 
	 * @param unknown $company
	 */
	public function update(Company $Company, $data)
	{
		$country = $this->em->getRepository('MainCommonBundle:Geo\Country')->findOneById($data['country']);
		$town = $this->em->getRepository('MainCommonBundle:Geo\Town')->findOneBy(array(
				'country' => $data['country'],
				'name'	  => $data['town']	
				));
		$Company->setTown($town);
		$Company->setCountry($country);
		$Company->setIdentification($data['identification']);
		$Company->setTitle($data['title']);
		$Company->setAddress($data['address']);
		$Company->setUpdatedAt(new \DateTime('now'));
		if($Company->getStatus()->getId() === 2) {
			$status = $this->em->getRepository('MainCommonBundle:Status\PhotographerStatus')->findOneById(1);//TODO change avec constant
			$Company->setStatus($status);
		}
		try{
			$this->em->persist($Company);
			$this->em->flush();
			return true;
		}catch(\Exception $e){
			var_dump($e->getMessage());
			return false;
		}
	}
}