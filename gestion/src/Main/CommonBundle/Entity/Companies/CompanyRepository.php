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
		$query = $qb->getQuery();
		$query->useQueryCache(true);	
		return $query->getSingleScalarResult();
	}

	public function countAllBy($arg) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('count(c)')
			->from('MainCommonBundle:Companies\Company', 'c')
			->where('c.status = :status')
			->setParameter('status', $arg);
		$query = $qb->getQuery();
		$query->useQueryCache(true);
		return $query->getSingleScalarResult();
	}

	/**
	 * [getCompany description]
	 * @param  [type] $user [description]
	 * @return [type]       [description]
	 */
	public function getCompany($user)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('c')
			->from('MainCommonBundle:Companies\Company', 'c')
			->where('c.photographer = :user')
			->setParameter('user', $user);	
		$query = $qb->getQuery();
		$query->useQueryCache(true);
		$query->useResultCache(true);
		$query->useResultCache(true, 21600, 'getCompany');
		return $query->getSingleResult();
	}
}