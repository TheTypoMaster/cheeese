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
	
	public function getCompanyDevis($company)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('d')
			->from('MainCommonBundle:Photographers\Devis', 'd')
			->where('d.company = :company')
			->setParameter('company', $company);
		$query = $qb->getQuery();
		$query->setResultCacheId('getCompanyDevis_'.$company);
		$query->useQueryCache(true);
		$query->useResultCache(true, 10800, 'getCompanyDevis_'.$company); //3h
		return $query->getResult();

	}
	/**
	 * 
	 * @param unknown $categoryId
	 * @param unknown $townId
	 * @param unknown $day
	 */
	public function findDevisFront($categoryId, $townId, $day, $min, $max) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('d', 'b', 'p')
			->from('MainCommonBundle:Photographers\Devis', 'd')
			->join('d.company', 'c')
			->innerJoin('MainCommonBundle:Photographers\DevisBook', 'b', 'WITH', 'd.id = b.devis')
			->innerJoin('MainCommonBundle:Photographers\DevisPrices', 'p', 'WITH', 'd.id = p.devis')
			->where('c.status = :status')
			->andWhere('d.active = :active')
			->andWhere('b.profile = :profile')
			->andWhere('p.active = :price')
			->setParameters(array(
					'status'	 => 2,			
					'active'	 => 1,
					'profile'	 => 1,
					'price'		 => 1
					));
			
			if ($categoryId != null) {
				$qb->andwhere('d.category = :categoryId')
					->setParameter('categoryId', $categoryId);
			}
			elseif ($townId != null) {
				$qb->innerJoin('MainCommonBundle:Photographers\Move', 'm', 'WITH', 'd.company = m.company')
					->andwhere('m.town = :town')
					->setParameter('town', $townId);
			}
			if ($day != null) {
				$qb->innerJoin('MainCommonBundle:Photographers\Availability', 'a', 'WITH', 'd.company = a.company')
				->andwhere('a.day = :day')
				->setParameter('day',$day);
			}	
			
		$query = $qb->getQuery();
		$query->setResultCacheId('findDevisFront_'.$categoryId.'_'.$townId.'_'.$day);
		$query->useQueryCache(true);
		$query->useResultCache(true, 1200, 'findDevisFront_'.$categoryId.'_'.$townId.'_'.$day);
		return $query->getResult();
	}
	/**
	 * [countAll description]
	 * @return [type] [description]
	 */
	public function countAllActive($user) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('count(d.id)')
			->from('MainCommonBundle:Photographers\Devis', 'd')
			->where('d.active = :active')
			->setParameter('active', 1);
		if ($user != null) {
			$qb->andwhere('d.company = :company')
			   ->setParameter('company', $user);
		}
		$query = $qb->getQuery();
		$query->useQueryCache(true);
		return $query->getSingleScalarResult();
	}

	public function groupBy($user) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('c.name as label','count(d.id) as value')
			->from('MainCommonBundle:Photographers\Devis', 'd')
			->innerJoin('d.category', 'c');
		if ($user != null) {
		$qb->where('d.company = :company')
		   ->setParameter('company', $user);
		}
		$qb->groupBy('c.name')
			->having('count(d.id) > :value')
			->setParameter('value', 0);
		$query = $qb->getQuery();
		$query->useQueryCache(true);
		return $qb->getQuery()->getResult();
	}

	public function updateNotation($devis, $date)
	{
		$qb = $this->_em->createQueryBuilder();
		$note = $qb->select('avg(e.prestation_notation) as average', 'count(e.prestation_notation) as total')
				->from('MainCommonBundle:Prestations\Evaluation', 'e')
				->join('e.prestation', 'p')
				->join('p.devis', 'd')
				->where('d.id = :devis')
        		->setParameter('devis', $devis)
				->getQuery()
				->getSingleResult();

		if(isset($note['average']) && isset($note['total']))
		{
			$q = $qb->update('MainCommonBundle:Photographers\Devis', 'd')
        			->set('d.note', $note['average'])
        			->set('d.prestations', $note['total'])
        			->set('d.updatedAt', ':date')
        			->where('d.id = :id')
        			->setParameters(array(
        				'id' 	=> $devis,
        				'date'	=> $date
        				)
        			)
        			->getQuery();
			$q->execute();		
		}
	}
	
	/**
	 * [getDevisPublic description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function getDevisPublic($id)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('d')
			->from('MainCommonBundle:Photographers\Devis', 'd')
			->innerJoin('MainCommonBundle:Companies\Company', 'c', 'WITH', 'd.company = c.id')
			->innerJoin('MainCommonBundle:Photographers\DevisBook', 'b', 'WITH', 'd.id = b.devis')
			->innerJoin('MainCommonBundle:Photographers\DevisPrices', 'p', 'WITH', 'd.id = p.devis')
			->where('d.id = :id')
			->andwhere('c.status = :status')
			->andWhere('d.active = :active')
			->andWhere('b.profile = :profile')
			->andWhere('p.active = :price')
			->setParameters(array(
					'id' 		 => $id,
					'active'	 => 1,
					'profile'	 => 1,
					'price'		 => 1,
					'status'	 => 2
					));
		$query = $qb->getQuery();
		$query->setResultCacheId('getDevisPublic_'.$id);
		$query->useQueryCache(true);
		$query->useResultCache(true, 3600, 'getDevisPublic_'.$id);
		return $query->getOneOrNullResult();
	}
	
}
