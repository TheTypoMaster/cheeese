<?php
namespace Main\CommonBundle\Listener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Main\CommonBundle\Entity\Messages\Message as Message;

class MessageListener
{	
    /** @ORM\PostPersist */
    public function postPersist(Message $message, LifecycleEventArgs $event)
    {
        $entityManager = $event->getEntityManager();
        if ($message->getType() == 1) {
        	$cacheDriver = $entityManager->getConfiguration()->getResultCacheImpl();
        	$cacheDriver->delete('getUnreadPrestationMessages_'.$message->getReceiver()->getId().'_'.$message->getPrestation()->getId());
        	$cacheDriver->delete('getUnreadPrestationsMessages_'.$message->getReceiver()->getId());
    	}      
    }

    /** @ORM\PostUpdate */
    public function postUpdate(Message $message, LifecycleEventArgs $event) 
    {  
    	$entityManager = $event->getEntityManager();
        if ($message->getType() == 1) {
        	$cacheDriver = $entityManager->getConfiguration()->getResultCacheImpl();
        	$cacheDriver->delete('getUnreadPrestationMessages_'.$message->getReceiver()->getId().'_'.$message->getPrestation()->getId());
        	$cacheDriver->delete('getUnreadPrestationsMessages_'.$message->getReceiver()->getId());
    	}        
    }
}