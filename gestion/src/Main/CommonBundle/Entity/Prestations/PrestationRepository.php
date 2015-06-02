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
			->where('d.company = :photographer')
			->setParameter('photographer', $user);		
		return $qb->getQuery()->getResult();
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
		->where('p.id = :id')
		->andWhere('d.company = :photographer')
		->setParameters(array(
				'id'			=> $id,
				'photographer'	=> $user
				));
		return $qb->getQuery()->getSingleResult();
	}
	
}