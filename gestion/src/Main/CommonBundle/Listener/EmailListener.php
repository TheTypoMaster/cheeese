<?php
namespace Main\CommonBundle\Listener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Main\CommonBundle\Entity\Users\Preference as Preference;

class EmailListener
{	
    /** @ORM\PostPersist */
    public function postPersist(Preference $preference, LifecycleEventArgs $event)
    {
        $entityManager = $event->getEntityManager();
        $cacheDriver = $entityManager->getConfiguration()->getResultCacheImpl();
        $cacheDriver->delete('getUserPreferences_'.$preference->getUser()->getId());
    }

    /** @ORM\PostUpdate */
    public function postUpdate(Preference $preference, LifecycleEventArgs $event) 
    {  
    	$entityManager = $event->getEntityManager();
        $cacheDriver = $entityManager->getConfiguration()->getResultCacheImpl();
        $cacheDriver->delete('getUserPreferences_'.$preference->getUser()->getId());        
    }
}