<?php

namespace Main\CommonBundle\Entity\Users;

use Doctrine\ORM\EntityRepository;

/**
 * CommuneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
	/**
	 * Retrieve all roles photographers
	 * @param unknown $user
	 */
	public function getUsersByRole($role) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('u')
			->from('MainCommonBundle:Users\User', 'u')
			->where('u.roles LIKE :roles')
        	->setParameter('roles', '%"'.$role.'"%');	
		return $qb->getQuery()->getResult();
	}

	public function updateClientNotation($user, $date)
	{
		$qb = $this->_em->createQueryBuilder();
		$note = $qb->select('avg(e.user_notation) as average', 'count(e.user_notation) as total')
				->from('MainCommonBundle:Prestations\Evaluation', 'e')
				->join('e.prestation', 'p')
				->where('p.client = :client')
				->andWhere('e.scorer != :client')
        		->setParameter('client', $user)
				->getQuery()->getSingleResult();

		if(isset($note['average']) && isset($note['total']))
		{
			$this->updateNote($user, $date, $note);
		}

	}

	public function updatePhotographerNotation($user, $date)
	{
		$qb = $this->_em->createQueryBuilder();
		$note = $qb->select('avg(e.user_notation) as average', 'count(e.user_notation) as total')
				->from('MainCommonBundle:Prestations\Evaluation', 'e')
				->join('e.prestation', 'p')
				->join('p.devis', 'd')
				->join('d.company', 'c')
				->where('c.photographer = :client')
				->andWhere('e.scorer != :client')
        		->setParameter('client', $user)
				->getQuery()
				->getSingleResult();

		if(isset($note['average']) && isset($note['total']))
		{
			$this->updateNote($user, $date, $note);
		}

	}

	/**
	 * [updateNote description]
	 * @param  [type] $user [description]
	 * @param  [type] $date [description]
	 * @param  [type] $note [description]
	 * @return [type]       [description]
	 */
	private function updateNote($user, $date, $note)
	{
		$qb = $this->_em->createQueryBuilder();
		$q = $qb->update('MainCommonBundle:Users\User', 'u')
        			->set('u.note', $note['average'])
        			->set('u.prestations', $note['total'])
        			->set('u.updatedAt', ':date')
        			->where('u.id = :id')
        			->setParameters(array(
        				'id' 	=> $user,
        				'date'	=> $date
        				)
        			)
        			->getQuery();
			$q->execute();
	}
	
}