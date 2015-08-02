<?php 

namespace Main\CommonBundle\Services\Geo;
use Doctrine\ORM\EntityManager;
use Main\CommonBundle\Entity\Companies\Company;

class ServiceTown 
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
		$this->repository = $this->em->getRepository('MainCommonBundle:Geo\Town');
	}	
	
	/**
	 * 
	 * @param unknown $country
	 * @param unknown $data
	 */
	public function findByDepartment($department, $country)
	{
		return $this->repository->findByDeptAndCountry($department, $country);	
	}

	/**
	 * [findTownsByCompany description]
	 * @param  [type] $company [description]
	 * @return [type]          [description]
	 */
	public function findTownsByCompany(Company $company)
	{
		return $this->repository->findTownsByCompany($company->getId());
	}

	public function isTownAvailable($arg, $text, $towns)
	{
		$result = null;
		foreach ($towns as $town) {
			if($town['id'] == $arg) {
				$result = array('id' => $arg, 'text' => $text);
			}
		}
		return $result;
	}
}