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

	/**
	 * [countAllBy description]
	 * @param  [type] $arg [description]
	 * @return [type]      [description]
	 */
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
		$query->setResultCacheId('getCompany_'.$user);
		$query->useQueryCache(true);
		$query->useResultCache(true, 21600, 'getCompany_'.$user);//6h
		return $query->getOneOrNullResult();
	}

	/**
	 * [getCompaniesByDept description]
	 * @param  [type] $dept [description]
	 * @return [type]       [description]
	 */
	public function getCompaniesByDept($dept)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('c')
			->from('MainCommonBundle:Companies\Company', 'c')
			->innerJoin('c.town', 't')
			->where('t.department = :dept')
			->setParameter('dept', $dept);	
		$query = $qb->getQuery();
		$query->setResultCacheId('getCompaniesByDept_'.$dept);
		$query->useQueryCache(true);
		$query->useResultCache(true, 3600, 'getCompaniesByDept_'.$dept);//6h
		return $qb->getQuery()->getResult();
	}

	/**
	 * [groupBy description]
	 * @param  [type] $user [description]
	 * @return [type]       [description]
	 */
	public function groupByDept($dept) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('s.libelle as label','count(c.id) as value')
			->from('MainCommonBundle:Companies\Company', 'c')
			->innerJoin('c.town', 't')
			->innerJoin('c.status', 's')
			->where('t.department = :dept')
			->setParameter('dept', $dept)
		 	->groupBy('s.libelle')
			->having('count(s.id) > :value')
			->setParameter('value', 0);
		$query = $qb->getQuery();
		$query->setResultCacheId('groupByDept'.$dept);
		$query->useQueryCache(true);
		$query->useResultCache(true, 3600, 'groupByDept'.$dept);//6h
		return $qb->getQuery()->getResult();
	}
}