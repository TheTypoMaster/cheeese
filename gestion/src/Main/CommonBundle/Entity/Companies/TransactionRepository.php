<?php 
namespace Main\CommonBundle\Entity\Companies;
use Doctrine\ORM\EntityRepository;

class TransactionRepository extends EntityRepository
{

	public function getTotalMoney($arg) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('sum(t.price)')
			->from('MainCommonBundle:Companies\Transaction', 't')
			->where('t.photographer = :photographer')
			->setParameter('photographer', $arg);	
		return $qb->getQuery()->getSingleScalarResult();
	}
}