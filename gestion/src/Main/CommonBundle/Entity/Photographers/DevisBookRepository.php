<?php

namespace Main\CommonBundle\Entity\Photographers;

use Doctrine\ORM\EntityRepository;

/**
 * CommuneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DevisBookRepository extends EntityRepository
{
	/**
	 * [getPricesByDuration description]
	 * @param  [type] $devis [description]
	 * @return [type]        [description]
	 */
	public function getBook($devis)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('db')
			->from('MainCommonBundle:Photographers\DevisBook', 'db')
			->where('db.devis = :devis')
			->setParameter('devis', $devis);
		$query = $qb->getQuery();
		$query->setResultCacheId('getBook_'.$devis);
		$query->useQueryCache(true);
		$query->useResultCache(true, 3600, 'getBook_'.$devis);
		return $query->getResult();
	}	
}
