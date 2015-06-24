<?php 

namespace Main\CommonBundle\Services\Geo;
use Doctrine\ORM\EntityManager;
use Main\CommonBundle\Entity\Geo\Department;


class ServiceDepartment
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
	
	public function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
		$this->repository = $this->em->getRepository('MainCommonBundle:Geo\Department');
	}	

	/**
	 * [getAllDepts description]
	 * @return [type] [description]
	 */
	public function getAllDepts()
	{

		return $this->repository->findAll();
	}

	/**
	 * [findByCodeAndCountry description]
	 * @param  [type] $code    [description]
	 * @param  [type] $country [description]
	 * @return [type]          [description]
	 */
	public function findByCodeAndCountry($code, $country)
	{
		return $this->repository->findOneBy(array(
			'code' 		=> $code,
			'country'	=> $country
			));
	}

	/**
	 * [updateDept description]
	 * @param  Department $dept [description]
	 * @return [type]           [description]
	 */
	public function updateDept(Department $dept)
	{
		if($dept->getActive() != 2) 
		{
			$active = $dept->getActive() + 1;
			$dept->setActive($active);
			try{
				$this->em->flush();				
				return true;
			}catch(\Exception $e){
				var_dump($e->getMessage());
				return false;
			}
		}
	}
}