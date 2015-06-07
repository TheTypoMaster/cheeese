<?php 
namespace Main\CommonBundle\Entity\Companies;
use Doctrine\ORM\EntityRepository;

class CompanyRepository extends EntityRepository
{
	/**
	 * Retrieve all roles photographers
	 * @param unknown $user
	 */
	public function getCountUnverifiedCompanies() {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('count(c)')
			->from('MainCommonBundle:Companies\Company', 'c')
			->where('c.status = :status')
			->setParameter('status', 1);	
		return $qb->getQuery()->getSingleScalarResult();
	}
}