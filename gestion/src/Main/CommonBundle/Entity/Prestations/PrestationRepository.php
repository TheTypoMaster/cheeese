<?php

namespace Main\CommonBundle\Entity\Prestations;

use Doctrine\ORM\EntityRepository;

/**
 * CommuneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PrestationRepository extends EntityRepository
{
	/**
	 * Retrieve all photographer's services
	 * @param unknown $user
	 */
	public function findPhotographerServices($user) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('p')
			->from('MainCommonBundle:Prestations\Prestation', 'p')
			->innerJoin('p.devis', 'd')
			->innerJoin('d.company', 'c')
			->where('c.photographer = :photographer')
			->setParameter('photographer', $user);
		$query = $qb->getQuery();
		$query->useQueryCache(true);	
		return $query->getResult();
	}
	
	/**
	 * 
	 * @param unknown $user
	 * @param unknown $id
	 */
	public function findPhotographerServiceById($user, $id) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('p')
		->from('MainCommonBundle:Prestations\Prestation', 'p')
		->innerJoin('p.devis', 'd')
		->innerJoin('d.company', 'c')
		->where('p.id = :id')
		->andWhere('c.photographer = :photographer')
		->setParameters(array(
				'id'			=> $id,
				'photographer'	=> $user
				));
		$query = $qb->getQuery();
		$query->useQueryCache(true);
		return $query->getSingleResult();
	}

	public function countAll($user) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('count(p.id)')
			->from('MainCommonBundle:Prestations\Prestation', 'p');
			if ($user != null) {
				$qb->innerJoin('p.devis', 'd')
				   ->innerJoin('d.company', 'c')
				   ->where('c.photographer = :company')
				   ->setParameter('company', $user);
			}
		$query = $qb->getQuery();
		$query->useQueryCache(true);
        return $query->getSingleScalarResult();
	}

	public function groupBy($user) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('s.libelle as y','count(p.id) as a')
			->from('MainCommonBundle:Prestations\Prestation', 'p')
			->innerJoin('p.status', 's');
		if ($user != null) {
			$qb->innerJoin('p.devis', 'd')
			   ->innerJoin('d.company', 'c')
			   ->where('c.photographer = :company')
			   ->setParameter('company', $user);
		}
		$qb->groupBy('s.libelle')
			->having('count(p.id) > :value')
			->setParameter('value', 0);
		$query = $qb->getQuery();
		$query->useQueryCache(true);
		return $query->getResult();
	}

	/**
	 * [getWeekPrestations description]
	 * @param  [type] $date   [description]
	 * @param  [type] $status [description]
	 * @param  [type] $user   [description]
	 * @return [type]         [description]
	 */
	public function getWeekPrestations($date, $status, $user)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('p')
		->from('MainCommonBundle:Prestations\Prestation', 'p')
		->innerJoin('p.devis', 'd')
		->innerJoin('d.company', 'c')
		->where('p.startTime < :date')
		->andWhere('c.photographer = :photographer')
		->andWhere('p.status = :status')
		->setParameters(array(
				'date'			=> $date,
				'photographer'	=> $user,
				'status'		=> $status
				))
		->orderBy('p.startTime', 'ASC');
		$query = $qb->getQuery();
		$query->useQueryCache(true);
		$query->useResultCache(true, 21600, 'getWeekPrestations'); //6h
		return $query->getResult();	
	}

	/**
	 * [setPassedPrestations description]
	 * @param [type] $date      [description]
	 * @param [type] $day       [description]
	 * @param [type] $status    [description]
	 * @param [type] $newStatus [description]
	 */
	public function setPassedPrestations($date, $status, $newStatus)
	{
		$qb = $this->_em->createQueryBuilder();
		$q = $qb->update('MainCommonBundle:Prestations\Prestation', 'p')
        		->set('p.status', ':new')
        		->set('p.updatedAt', ':date')
        		->where('p.status = :old')
        		->andWhere('p.startTime < :day')
        			->setParameters(array(
        				'new'	=> $newStatus,
        				'old'	=> $status,
        				'date'	=> $date,
        				'day'	=> $date->format('Y-m-d')
        				)
        			)
        			->getQuery();
			$q->execute();	
	}

	
	
}