<?php

namespace Main\CommonBundle\Entity\Photographers;

use Doctrine\ORM\EntityRepository;

/**
 * CommuneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DevisPricesRepository extends EntityRepository
{
	/**
	 * [getPricesByDuration description]
	 * @param  [type] $devis [description]
	 * @return [type]        [description]
	 */
	public function getPricesByDuration($devis)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('p.price as price', 'd.id', 'd.libelle')
			->from('MainCommonBundle:Photographers\DevisPrices', 'p')
			->leftjoin('p.duration', 'd')
			->where('p.devis = :devis')
			->setParameter('devis', $devis);
		$query = $qb->getQuery();
		$query->setResultCacheId('getPricesByDuration_'.$devis);
		$query->useQueryCache(true);
		$query->useResultCache(true, 3600, 'getPricesByDuration_'.$devis);
		return $query->getResult();
	}

	public function getPricesPublic($devis)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('p.price as price', 'd.id')
			->from('MainCommonBundle:Photographers\DevisPrices', 'p')
			->leftjoin('p.duration', 'd')
			->where('p.devis = :devis')
			->andWhere('p.active = :active')
			->setParameters(array(
				'devis' 	=> $devis,
				'active'	=> 1
				));
		$query = $qb->getQuery();
		$query->setResultCacheId('getPricespublic_'.$devis);
		$query->useQueryCache(true);
		$query->useResultCache(true, 3600, 'getPricespublic_'.$devis);
		return $query->getResult();
	}
	
}
