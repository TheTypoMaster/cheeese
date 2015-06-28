<?php

namespace Main\CommonBundle\Entity\Photographers;

use Doctrine\ORM\EntityRepository;

/**
 * CommuneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DevisRepository extends EntityRepository
{
	
	/**
	 * 
	 * @param unknown $categoryId
	 * @param unknown $townId
	 * @param unknown $day
	 */
	public function findDevisFront($categoryId, $townId, $day) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('d', 'b', 'p')
			->from('MainCommonBundle:Photographers\Devis', 'd')
			->join('d.company', 'c')
			->innerJoin('MainCommonBundle:Photographers\Availability', 'a', 'WITH', 'd.company = a.company')
			->innerJoin('MainCommonBundle:Photographers\Move', 'm', 'WITH', 'd.company = m.company')
			->innerJoin('MainCommonBundle:Photographers\DevisBook', 'b', 'WITH', 'd.id = b.devis')
			->innerJoin('MainCommonBundle:Photographers\DevisPrices', 'p', 'WITH', 'd.id = p.devis')
			->where('c.status = :status')
			->andwhere('d.category = :categoryId')
			->andwhere('a.day = :day')
			->andwhere('m.town = :town')
			->andWhere('d.active = :active')
			->andWhere('b.profile = :profile')
			->andWhere('p.active = :price')
			->setParameters(array(
					'status'	 => 2,
					'categoryId' => $categoryId,
					'day'		 => $day,
					'town'		 => $townId,
					'active'	 => 1,
					'profile'	 => 1,
					'price'		 => 1
					));
		return $qb->getQuery()->getResult();
	}
	/**
	 * [countAll description]
	 * @return [type] [description]
	 */
	public function countAllActive() {
		$qb = $this->_em->createQueryBuilder();
		return $qb->select('count(d.id)')
			->from('MainCommonBundle:Photographers\Devis', 'd')
			->where('d.active = :active')
			->setParameter('active', 1)
            ->getQuery()
            ->getSingleScalarResult();
	}

	public function groupBy($user) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('c.name as label','count(d.id) as value')
			->from('MainCommonBundle:Photographers\Devis', 'd')
			->innerJoin('d.category', 'c');
		$qb->groupBy('c.name')
			->having('count(d.id) > :value')
			->setParameter('value', 0);
		return $qb->getQuery()->getResult();
	}
	
}
