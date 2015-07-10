<?php 
namespace Main\CommonBundle\Entity\Companies;
use Doctrine\ORM\EntityRepository;

class IbanRepository extends EntityRepository
{
	/**
	 * Retrieve all roles photographers
	 * @param unknown $user
	 */
	public function getIban($user) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('i')
			->from('MainCommonBundle:Companies\Iban', 'i')
			->where('i.photographer = :user')
			->setParameter('user', $user);	

		$query = $qb->getQuery();
		$query->useQueryCache(true);
		$query->useResultCache(true);
		$query->useResultCache(true, 21600, 'getIban');
		return $qb->getQuery()->getSingleResult();
	}
}