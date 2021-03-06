<?php

namespace Main\CommonBundle\Entity\Messages;

use Doctrine\ORM\EntityRepository;

/**
 * CommuneRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessageRepository extends EntityRepository
{
	/**
	 * Ajout des messages d'historique
	 * @param  int $id       [description]
	 * @param  int $status   [description]
	 * @param  int $sender   [description]
	 * @param  int $receiver [description]
	 * @param  int $message  [description]
	 * @return string           [description]
	 */
	public function createMessagePrestation($prestation, $type, $model, $sender, $receiver, $message)
	{

		$date = new \DateTime('now');
		$this->_em->getConnection()->insert('messages.message', array(
				'sender' 		=> $sender,
				'receiver' 		=> $receiver,
				'type' 			=> $type,
				'model'			=> $model,
				'prestation' 	=> $prestation,
				'read' 			=> 1,
				'content' 		=> $message,
				'createdAt'		=> $date->format('Y-m-d H:i:s'),
				'updatedAt' 	=> $date->format('Y-m-d H:i:s'),
				));	
	}

	/**
	 * [getUnreadPrestationMessages description]
	 * @param  [type] $user [description]
	 * @return [type]       [description]
	 */
	public function getUnreadPrestationsMessages($user) {
		$qb = $this->_em->createQueryBuilder();
		$qb->select('m')
			->from('MainCommonBundle:Messages\Message', 'm')
			->where('m.receiver = :user')
			->andWhere('m.read = :status')
			->andWhere('m.type = :type')
			->setParameters(array(
					'user'  	=> $user,
					'status'	=> 0,
					'type'		=> 1 //Concerne les prestations uniquement
				));
		$query = $qb->getQuery();
		$query->setResultCacheId('getUnreadPrestationsMessages_'.$user);
		$query->useQueryCache(true);
		$query->useResultCache(true, 3600, 'getUnreadPrestationsMessages_'.$user);//1h
		return $query->getResult();
	}
	
	/**
	 * [getUnreadPrestationMessages description]
	 * @param  [type] $user       [description]
	 * @param  [type] $prestation [description]
	 * @return [type]             [description]
	 */
	public function getUnreadPrestationMessages($user, $prestation)
	{
		$qb = $this->_em->createQueryBuilder();
		$qb->select('m')
			->from('MainCommonBundle:Messages\Message', 'm')
			->where('m.receiver = :user')
			->andWhere('m.read = :status')
			->andWhere('m.prestation = :prestation')
			->setParameters(array(
					'user'  		=> $user,
					'status'		=> 0,
					'prestation'	=> $prestation //Concerne les prestations uniquement
				));
		$query = $qb->getQuery();
		$query->setResultCacheId('getUnreadPrestationMessages_'.$user.'_'.$prestation);
		$query->useQueryCache(true);
		$query->useResultCache(true, 3600, 'getUnreadPrestationMessages_'.$user.'_'.$prestation);//1h
		return $query->getResult();
	}
	
}