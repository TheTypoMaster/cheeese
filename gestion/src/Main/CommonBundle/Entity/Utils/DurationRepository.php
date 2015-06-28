<?php

namespace Main\CommonBundle\Entity\Utils;

use Doctrine\ORM\EntityRepository;

/**
 * CommuneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DurationRepository extends EntityRepository
{
	/**
	*/
    public function findDurationsByDevis($devis)
    {
    	$qb = $this->_em->createQueryBuilder();
		$qb->select('d')
			->from('MainCommonBundle:Utils\Duration', 'd')
			->innerJoin('MainCommonBundle:Photographers\DevisPrices', 'p', 'with', 'd.id = p.duration')
			->where('p.devis = :devis')
			->andWhere('p.active = :active')
			->setParameters(array(
				'active' => 1,
				'devis' => $devis
				));
			return $qb;
    } 
	
}