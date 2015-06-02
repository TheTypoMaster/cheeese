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
		$qb->select('d')
			->from('MainCommonBundle:Photographers\Devis', 'd')
			->join('d.company', 'c')
			->innerJoin('MainCommonBundle:Photographers\Availability', 'a', 'WITH', 'd.company = a.company')
			->innerJoin('MainCommonBundle:Photographers\Move', 'm', 'WITH', 'd.company = m.company')
			->where('c.status = :status')
			->andwhere('d.category = :categoryId')
			->andwhere('a.day = :day')
			->andwhere('m.town = :town')
			->setParameters(array(
					'status'	 => 1,
					'categoryId' => $categoryId,
					'day'		 => $day,
					'town'		 => $townId
					));
		return $qb->getQuery()->getResult();
	}
	
}
