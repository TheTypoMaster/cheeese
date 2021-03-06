<?php

namespace Main\CommonBundle\Entity\Prestations;

use Doctrine\ORM\EntityRepository;

/**
 * CommuneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EvaluationRepository extends EntityRepository
{
	/**
	 * Retrieve all photographer's services
	 * @param unknown $user
	 */
	public function getbyDevis($devis) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('e')
			->from('MainCommonBundle:Prestations\Evaluation', 'e')
			->innerJoin('e.prestation', 'p')
			->innerJoin('p.devis', 'd')
			->where('d.id = :devis')
			->andWhere($qb->expr()->isNotNull('e.prestation_notation'))			
			->setParameter('devis', $devis);
		$query = $qb->getQuery();
		$query->setResultCacheId('getbyDevis'.$devis);
		$query->useQueryCache(true);
		$query->useResultCache(true, 3600, 'getbyDevis'.$devis); //1h
		return $query->getResult();
	}	
}