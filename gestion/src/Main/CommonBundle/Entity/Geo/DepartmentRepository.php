<?php 
namespace Main\CommonBundle\Entity\Geo;

use Doctrine\ORM\EntityRepository;

class DepartmentRepository extends EntityRepository
{
	
	/**
	 * 
	 * @param unknown $countryId
	 * @param unknown $contient
	 */
	public function findAvailabeDepts($value) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('d')
			->from('MainCommonBundle:Geo\Department', 'd')
			->where('d.active > :active')
			->setParameter('active', $value);
			return $qb->getQuery()->getResult();
	}

}