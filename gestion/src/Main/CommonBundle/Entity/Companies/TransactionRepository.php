<?php 
namespace Main\CommonBundle\Entity\Companies;
use Doctrine\ORM\EntityRepository;

class TransactionRepository extends EntityRepository
{

	public function getTotalMoney($user) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('sum(t.price)')
			->from('MainCommonBundle:Companies\Transaction', 't')
			->where('t.photographer = :photographer')
			->setParameter('photographer', $user);	
		$query = $qb->getQuery();
		$query->useResultCache(true);
		$query->useResultCache(true, 21600, 'getTotalMoney');
		return $qb->getQuery()->getSingleScalarResult();
	}

	public function getTransactions($user)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('t')
			->from('MainCommonBundle:Companies\Transaction', 't')
			->where('t.photographer = :user')
			->setParameter('user', $user);	

		$query = $qb->getQuery();
		$query->useResultCache(true);
		$query->useResultCache(true, 21600, 'getTransactions');
		return $qb->getQuery()->getResult();
	}
}