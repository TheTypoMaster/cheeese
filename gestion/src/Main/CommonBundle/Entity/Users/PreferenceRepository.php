<?php

namespace Main\CommonBundle\Entity\Users;

use Doctrine\ORM\EntityRepository;

/**
 * CommuneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PreferenceRepository extends EntityRepository
{
	/**
	 * Retrieve all roles photographers
	 * @param unknown $user
	 */
	public function getUserPreferences($user) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('p')
			->from('MainCommonBundle:Users\Preference', 'p')
			->where('p.user = :user')
        	->setParameter('user', $user);
        $query = $qb->getQuery();
		$query->setResultCacheId('getUserPreferences_'.$user);
		$query->useQueryCache(true);
		$query->useResultCache(true, 10800, 'getUserPreferences_'.$user); //3h	
		return $query->getOneOrNullResult();
	}
	
}