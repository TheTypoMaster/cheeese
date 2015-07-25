<?php
namespace Main\CommonBundle\Listener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Main\CommonBundle\Entity\Messages\Notification as Notification;

class NotificationListener
{	
    /** @ORM\PostPersist */
    public function postPersist(Notification $notification, LifecycleEventArgs $event)
    {
        $entityManager = $event->getEntityManager();
        if ($notification->getType() == 1) {
        	$cacheDriver = $entityManager->getConfiguration()->getResultCacheImpl();
        	$cacheDriver->delete('getUnreadPrestationNotifications_'.$notification->getReceiver()->getId().'_'.$notification->getPrestation()->getId());
        	$cacheDriver->delete('getUnreadPrestationsNotifications_'.$notification->getReceiver()->getId());
    	} 
             
    }

    /** @ORM\PostUpdate */
    public function postUpdate(Notification $notification, LifecycleEventArgs $event) 
    {  
        
    	$entityManager = $event->getEntityManager();
        if ($notification->getType() == 1) {
        	$cacheDriver = $entityManager->getConfiguration()->getResultCacheImpl();
        	$cacheDriver->delete('getUnreadPrestationNotifications_'.$notification->getReceiver()->getId().'_'.$notification->getPrestation()->getId());
        	$cacheDriver->delete('getUnreadPrestationsNotifications_'.$notification->getReceiver()->getId());
    	}
             
    }
}